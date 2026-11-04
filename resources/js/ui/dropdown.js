const instances = new Set();
let uid = 0;
let hooksBound = false;

function bindGlobalHooks() {
    if (hooksBound) return;
    hooksBound = true;
    document.addEventListener('livewire:navigating', () => {
        [...instances].forEach((i) => i.closeTree(false));
    });
}

const VIEWPORT_PADDING = 8;

export default function uiDropdown({ defaultOpen = false, sub = false } = {}) {
    return {
        open: !!defaultOpen,
        sub: !!sub,
        id: `dropdown-menu-${++uid}`,
        highlightedEl: null,

        _onResize: null,
        _onScroll: null,
        _onPointerDown: null,
        _onKeydown: null,
        _ro: null,
        _unwatch: null,
        _attached: false,
        _raf: null,

        get state() {
            return this.open ? 'open' : 'closed';
        },

        init() {
            instances.add(this);
            bindGlobalHooks();

            this._onResize = () => this.schedulePosition();
            this._onScroll = () => this.schedulePosition();
            this._onPointerDown = (e) => this.handlePointerDown(e);
            this._onKeydown = (e) => {
                if (e.defaultPrevented || e.key !== 'Escape' || !this.open) return;
                if (this.children().some((c) => c.open)) return;
                e.preventDefault();
                this.closeTree(true);
            };

            const t = this.triggerNode();
            if (t && !t.id) t.id = `${this.id}-trigger`;

            this._unwatch = this.$watch('open', (v) => (v ? this.afterOpen() : this.afterClose()));
            if (this.open) this.afterOpen();
        },

        destroy() {
            this.detach();
            if (this._unwatch) this._unwatch();
            if (this._raf) cancelAnimationFrame(this._raf);
            this.highlightedEl = null;
            instances.delete(this);
        },

        triggerNode() {
            const slot = this.sub ? 'dropdown-menu-sub-trigger' : 'dropdown-menu-trigger';
            return this.$root.querySelector(`[data-slot="${slot}"]`);
        },
        contentNode() {
            return document.getElementById(this.id);
        },
        wrapperNode() {
            const c = this.contentNode();
            const p = c ? c.parentElement : null;
            return p && p.hasAttribute('data-popper-content-wrapper') ? p : null;
        },

        children(acc = []) {
            const c = this.contentNode();
            if (!c) return acc;
            for (const i of instances) {
                if (i === this || acc.includes(i)) continue;
                if (i.$root && c.contains(i.$root)) {
                    acc.push(i);
                    i.children(acc);
                }
            }
            return acc;
        },
        rootInstance() {
            let node = this;
            let guard = 0;
            while (guard++ < 20) {
                const parent = [...instances].find(
                    (i) => i !== node && i.contentNode()?.contains(node.$root)
                );
                if (!parent) return node;
                node = parent;
            }
            return node;
        },
        ownsTarget(target) {
            const tree = [this, ...this.children()];
            return tree.some((i) => {
                const t = i.triggerNode();
                const w = i.wrapperNode();
                return (t && t.contains(target)) || (w && w.contains(target));
            });
        },

        toggle() {
            this.open ? this.closeTree(true) : (this.open = true);
        },
        show(force = true) {
            this.open = force === undefined ? true : !!force;
        },
        close() {
            this.open = false;
        },
        closeAll() {
            this.rootInstance().closeTree(true);
        },
        closeTree(focusTrigger = false) {
            this.children().forEach((c) => c.closeTree(false));
            this.open = false;
            if (focusTrigger) {
                const t = this.triggerNode();
                if (t) this.$nextTick(() => t.focus());
            }
        },

        afterOpen() {
            this.attach();
            const parent = [...instances].find((i) => i.contentNode()?.contains(this.$root));
            if (parent) {
                parent.children().forEach((sib) => {
                    if (sib !== this && sib.open && sib.$root.parentElement === this.$root.parentElement) {
                        sib.closeTree(false);
                    }
                });
            }
            this.$nextTick(() => this.schedulePosition());
        },
        afterClose() {
            this.clearHighlight();
            this.detach();
        },

        attach() {
            if (this._attached) return;
            this._attached = true;
            window.addEventListener('resize', this._onResize, { passive: true });
            window.addEventListener('scroll', this._onScroll, { passive: true, capture: true });
            document.addEventListener('pointerdown', this._onPointerDown);
            document.addEventListener('keydown', this._onKeydown);
            if (typeof ResizeObserver !== 'undefined') {
                this._ro = new ResizeObserver(() => this.schedulePosition());
                const c = this.contentNode();
                if (c) this._ro.observe(c);
            }
        },
        detach() {
            if (!this._attached) return;
            this._attached = false;
            window.removeEventListener('resize', this._onResize);
            window.removeEventListener('scroll', this._onScroll, true);
            document.removeEventListener('pointerdown', this._onPointerDown);
            document.removeEventListener('keydown', this._onKeydown);
            if (this._ro) { this._ro.disconnect(); this._ro = null; }
            if (this._raf) { cancelAnimationFrame(this._raf); this._raf = null; }
        },

        handlePointerDown(e) {
            if (!this.open) return;
            const target = (e.composedPath && e.composedPath()[0]) || e.target;
            if (this.ownsTarget(target)) return;
            this.closeTree(false);
        },

        clearHighlight() {
            if (this.highlightedEl) this.highlightedEl.removeAttribute('data-highlighted');
            this.highlightedEl = null;
        },
        highlight(el, focus = false) {
            if (this.highlightedEl && this.highlightedEl !== el) {
                this.highlightedEl.removeAttribute('data-highlighted');
            }
            this.highlightedEl = el || null;
            if (!el) return;
            el.setAttribute('data-highlighted', '');
            if (focus) {
                el.focus({ preventScroll: true });
                el.scrollIntoView({ block: 'nearest' });
            }
        },
        items() {
            const c = this.contentNode();
            if (!c) return [];
            return Array.from(
                c.querySelectorAll('[data-slot="dropdown-menu-item"],[data-slot="dropdown-menu-sub-trigger"]')
            ).filter((el) => !el.hasAttribute('data-disabled') && el.getClientRects().length > 0);
        },
        navigate(step) {
            const list = this.items();
            if (!list.length) return;
            const cur = this.highlightedEl ? list.indexOf(this.highlightedEl) : -1;
            let next;
            if (step === 'first') next = 0;
            else if (step === 'last') next = list.length - 1;
            else {
                next = cur + step;
                if (next < 0) next = list.length - 1;
                if (next >= list.length) next = 0;
            }
            this.highlight(list[next], true);
        },

        onTriggerKeydown(e) {
            if (e.defaultPrevented) return;
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.open ? this.closeTree(true) : (this.open = true);
                if (this.open) this.$nextTick(() => this.navigate('first'));
            } else if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                this.open = true;
                this.$nextTick(() => this.navigate(e.key === 'ArrowDown' ? 'first' : 'last'));
            } else if (e.key === 'Escape' && this.open) {
                e.preventDefault();
                this.closeTree(true);
            }
        },

        onSubTriggerKeydown(e) {
            if (e.defaultPrevented) return;
            const rtl = this.isRtl();
            const openKey = rtl ? 'ArrowLeft' : 'ArrowRight';
            const closeKey = rtl ? 'ArrowRight' : 'ArrowLeft';
            if (e.key === 'Enter' || e.key === ' ' || e.key === openKey) {
                e.preventDefault();
                this.open = true;
                this.$nextTick(() => this.navigate('first'));
            } else if (e.key === closeKey || e.key === 'Escape') {
                e.preventDefault();
                this.closeTree(false);
                this.triggerNode()?.focus();
            } else if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
            }
        },

        onContentKeydown(e) {
            if (e.defaultPrevented) return;
            const target = e.target;
            const isSubTrigger = !!(target.closest && target.closest('[data-slot="dropdown-menu-sub-trigger"]'));
            const rtl = this.isRtl();

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault(); this.navigate(1); break;
                case 'ArrowUp':
                    e.preventDefault(); this.navigate(-1); break;
                case 'Home':
                    e.preventDefault(); this.navigate('first'); break;
                case 'End':
                    e.preventDefault(); this.navigate('last'); break;
                case 'Enter':
                case ' ': {
                    if (isSubTrigger) return;
                    const item = target.closest && target.closest('[data-slot="dropdown-menu-item"]');
                    if (item && !item.hasAttribute('data-disabled')) {
                        e.preventDefault();
                        item.click();
                    }
                    break;
                }
                case 'Escape':
                    e.preventDefault();
                    if (this.sub) { this.closeTree(false); this.triggerNode()?.focus(); }
                    else this.closeTree(true);
                    break;
                case 'Tab':
                    this.closeTree(false);
                    break;
                default:
                    if (this.sub && e.key === (rtl ? 'ArrowRight' : 'ArrowLeft')) {
                        e.preventDefault();
                        this.closeTree(false);
                        this.triggerNode()?.focus();
                    }
            }
        },

        isRtl() {
            return (document.documentElement.getAttribute('dir') || 'ltr').toLowerCase() === 'rtl';
        },
        schedulePosition() {
            if (!this.open) return;
            if (this._raf) cancelAnimationFrame(this._raf);
            this._raf = requestAnimationFrame(() => { this._raf = null; this.position(); });
        },
        position() {
            if (!this.open) return;
            const content = this.contentNode();
            const wrapper = this.wrapperNode();
            const trigger = this.triggerNode();
            if (!content || !wrapper || !trigger) return;

            let opts = {};
            try { opts = JSON.parse(content.dataset.options || '{}'); } catch (_) {}

            const rtl = this.isRtl();
            let side = opts.side ?? (this.sub ? 'inline-end' : 'bottom');
            if (side === 'inline-end') side = rtl ? 'left' : 'right';
            if (side === 'inline-start') side = rtl ? 'right' : 'left';

            const sideOffset = Number(opts.sideOffset ?? (this.sub ? 4 : 8));
            const align = opts.align ?? 'start';
            const alignOffset = Number(opts.alignOffset ?? 0);

            const vw = window.innerWidth;
            const vh = window.innerHeight;
            const r = trigger.getBoundingClientRect();
            const vertical = side === 'top' || side === 'bottom';

            const avail = vertical
                ? (side === 'bottom' ? vh - r.bottom - sideOffset - VIEWPORT_PADDING
                                     : r.top - sideOffset - VIEWPORT_PADDING)
                : vh - VIEWPORT_PADDING * 2;
            content.style.setProperty('--reka-dropdown-menu-content-available-height', `${Math.max(avail, 80)}px`);

            const w = wrapper.offsetWidth;
            const h = wrapper.offsetHeight;

            let top, left;
            if (vertical) {
                const below = vh - r.bottom - sideOffset;
                const above = r.top - sideOffset;
                let s = side;
                if (s === 'bottom' && below < h && above > below) s = 'top';
                else if (s === 'top' && above < h && below > above) s = 'bottom';
                side = s;
                top = side === 'bottom' ? r.bottom + sideOffset : r.top - sideOffset - h;
            } else {
                const rightSpace = vw - r.right - sideOffset;
                const leftSpace = r.left - sideOffset;
                let s = side;
                if (s === 'right' && rightSpace < w && leftSpace > rightSpace) s = 'left';
                else if (s === 'left' && leftSpace < w && rightSpace > leftSpace) s = 'right';
                side = s;
                left = side === 'right' ? r.right + sideOffset : r.left - sideOffset - w;
            }

            if (vertical) {
                if (align === 'end') left = rtl ? r.left + alignOffset : r.right - w - alignOffset;
                else if (align === 'center') left = r.left + r.width / 2 - w / 2 + alignOffset;
                else left = rtl ? r.right - w - alignOffset : r.left + alignOffset;
            } else {
                if (align === 'end') top = r.bottom - h - alignOffset;
                else if (align === 'center') top = r.top + r.height / 2 - h / 2 + alignOffset;
                else top = r.top + alignOffset;
            }

            left = Math.min(Math.max(left, VIEWPORT_PADDING), Math.max(vw - w - VIEWPORT_PADDING, VIEWPORT_PADDING));
            top = Math.min(Math.max(top, VIEWPORT_PADDING), Math.max(vh - h - VIEWPORT_PADDING, VIEWPORT_PADDING));

            // PENTING: Terapkan transform pada WRAPPER, bukan content
            wrapper.style.transform = `translate3d(${Math.round(left)}px, ${Math.round(top)}px, 0)`;

            content.setAttribute('data-side', side);
            const originX = vertical ? (align === 'end' ? 'right' : align === 'center' ? 'center' : 'left')
                                     : (side === 'right' ? 'left' : 'right');
            const originY = vertical ? (side === 'bottom' ? 'top' : 'bottom') : 'center';
            content.style.setProperty('--reka-dropdown-menu-content-transform-origin', `${originX} ${originY}`);
        },
    };
}