/**
 * Pulse PHP Application Framework — Unified Client Runtime
 * Zero-build, ultra-lightweight (<10KB) engine for SPA Navigation, Realtime, & Server-Driven Reactivity.
 */
(() => {
    class PulseRuntime {
        constructor() {
            this.components = new Map();
            this.debounceTimers = new Map();
            this.init();
        }

        init() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.boot());
            } else {
                this.boot();
            }
        }

        boot() {
            this.initSpaNavigation();
            this.scanAndMountComponents(document.body);
            console.log('%c⚡ Pulse Framework Runtime v1.0 initialized', 'color: #38bdf8; font-weight: bold; font-size: 12px;');
        }

        // ==========================================
        // 1. SPA Navigation Engine
        // ==========================================
        initSpaNavigation() {
            document.addEventListener('click', (e) => {
                const anchor = e.target.closest('a');
                if (!anchor) return;

                const href = anchor.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || anchor.hasAttribute('download') || anchor.getAttribute('target') === '_blank' || anchor.hasAttribute('data-native')) {
                    return;
                }

                const url = new URL(href, window.location.origin);
                if (url.origin !== window.location.origin) return;

                e.preventDefault();
                this.navigateTo(url.pathname + url.search + url.hash);
            });

            window.addEventListener('popstate', () => {
                this.fetchPage(window.location.pathname + window.location.search + window.location.hash, false);
            });
        }

        async navigateTo(url) {
            this.showProgressBar();
            try {
                await this.fetchPage(url, true);
            } catch (err) {
                console.error('Pulse SPA navigation error:', err);
                window.location.href = url;
            } finally {
                this.hideProgressBar();
            }
        }

        async fetchPage(url, push = true) {
            const res = await fetch(url, {
                headers: {
                    'X-Requested-Mode': 'spa',
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) {
                window.location.href = url;
                return;
            }

            const data = await res.json();
            if (push) {
                window.history.pushState({}, '', url);
            }

            if (data.title) {
                document.title = data.title;
            }

            // Target main content area
            const targetContainer = document.querySelector('#app');
            if (targetContainer) {
                const temp = document.createElement('div');
                temp.innerHTML = data.html;
                const inner = temp.querySelector('#app');
                targetContainer.innerHTML = inner ? inner.innerHTML : data.html;
                this.scanAndMountComponents(targetContainer);
            } else {
                window.location.reload();
                return;
            }

            // Update active navigation links
            document.querySelectorAll('.nav-link').forEach(link => {
                const linkHref = link.getAttribute('href');
                if (linkHref === url || (url === '/' && linkHref === '/')) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        showProgressBar() {
            let bar = document.getElementById('pulse-progress-bar');
            if (!bar) {
                bar = document.createElement('div');
                bar.id = 'pulse-progress-bar';
                bar.style.cssText = 'position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#38bdf8,#818cf8);width:0%;transition:width 0.2s ease;z-index:99999;box-shadow:0 0 10px rgba(56,189,248,0.9);';
                document.body.appendChild(bar);
            }
            bar.style.width = '45%';
            bar.style.opacity = '1';
        }

        hideProgressBar() {
            const bar = document.getElementById('pulse-progress-bar');
            if (bar) {
                bar.style.width = '100%';
                setTimeout(() => {
                    bar.style.opacity = '0';
                    setTimeout(() => { bar.style.width = '0%'; }, 250);
                }, 150);
            }
        }

        // ==========================================
        // 2. Reactive Component System
        // ==========================================
        scanAndMountComponents(root) {
            const elements = root.querySelectorAll('[data-component]');
            elements.forEach(el => this.mountComponent(el));
        }

        mountComponent(el) {
            const id = el.getAttribute('data-id');
            const snapshot = el.getAttribute('data-snapshot');
            const checksum = el.getAttribute('data-checksum');

            if (!id || !snapshot || !checksum) return;

            // Create wire proxy for client JS
            const wire = {
                id,
                get: (prop) => this.components.get(id)?.[prop],
                set: (prop, value, debounce = 150) => {
                    this.queueStateUpdate(el, prop, value, debounce);
                },
                call: (action, ...params) => {
                    this.dispatchAction(el, { action, params });
                },
                on: (event, callback) => {
                    if (!el._pulseListeners) el._pulseListeners = {};
                    if (!el._pulseListeners[event]) el._pulseListeners[event] = [];
                    el._pulseListeners[event].push(callback);
                }
            };
            el._wire = wire;

            // Execute co-located client script if present
            const clientScriptEl = el.querySelector('script[type="pulse/client"]');
            if (clientScriptEl && !el._clientMounted) {
                try {
                    const scriptFn = new Function('el', 'wire', clientScriptEl.textContent);
                    const module = scriptFn(el, wire);
                    if (module && typeof module.mounted === 'function') {
                        module.mounted(el, wire);
                    }
                    el._clientMounted = true;
                    el._clientModule = module;
                } catch (e) {
                    console.error(`[Pulse] Error in client script for ${componentName}:`, e);
                }
            }

            // Wire input bindings (bind="field")
            const boundInputs = el.querySelectorAll('[bind]');
            boundInputs.forEach(input => {
                const prop = input.getAttribute('bind');
                const debounce = parseInt(input.getAttribute('debounce') || '150', 10);

                const handler = () => {
                    const value = input.type === 'checkbox' ? input.checked : input.value;
                    this.queueStateUpdate(el, prop, value, debounce);
                };

                input.removeEventListener('input', input._pulseHandler);
                input._pulseHandler = handler;
                input.addEventListener('input', handler);
            });

            // Wire actions (action="increment" or action="deleteItem(1)")
            const actionElements = el.querySelectorAll('[action]');
            actionElements.forEach(btn => {
                const rawAction = btn.getAttribute('action');
                const eventType = btn.tagName === 'FORM' ? 'submit' : 'click';

                const handler = (e) => {
                    e.preventDefault();
                    this.executeAction(el, rawAction);
                };

                btn.removeEventListener(eventType, btn._pulseHandler);
                btn._pulseHandler = handler;
                btn.addEventListener(eventType, handler);
            });
        }

        queueStateUpdate(componentEl, prop, value, debounceMs) {
            const id = componentEl.getAttribute('data-id');
            const timerKey = `${id}:${prop}`;

            if (this.debounceTimers.has(timerKey)) {
                clearTimeout(this.debounceTimers.get(timerKey));
            }

            const timer = setTimeout(() => {
                this.debounceTimers.delete(timerKey);
                this.dispatchAction(componentEl, {
                    updates: { [prop]: value }
                });
            }, debounceMs);

            this.debounceTimers.set(timerKey, timer);
        }

        executeAction(componentEl, rawAction) {
            let actionName = rawAction;
            let params = [];

            const match = rawAction.match(/^([a-zA-Z0-9_]+)\((.*)\)$/);
            if (match) {
                actionName = match[1];
                const rawParams = match[2].split(',').map(s => s.trim().replace(/^['"]|['"]$/g, ''));
                params = rawParams.filter(s => s.length > 0);
            }

            this.dispatchAction(componentEl, {
                action: actionName,
                params: params
            });
        }

        async dispatchAction(componentEl, payload) {
            const id = componentEl.getAttribute('data-id');
            const snapshot = componentEl.getAttribute('data-snapshot');
            const checksum = componentEl.getAttribute('data-checksum');

            const loadingElements = componentEl.querySelectorAll(`[loading]`);
            loadingElements.forEach(l => l.classList.add('loading-active'));

            try {
                const res = await fetch('/_pulse/action', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Reactive-Action': 'true',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id,
                        snapshot,
                        checksum,
                        updates: payload.updates || {},
                        action: payload.action || null,
                        params: payload.params || []
                    })
                });

                if (!res.ok) {
                    const err = await res.json();
                    console.error('Pulse action error:', err);
                    return;
                }

                const data = await res.json();
                if (data.html) {
                    const temp = document.createElement('div');
                    temp.innerHTML = data.html.trim();
                    const newEl = temp.firstElementChild;

                    if (newEl) {
                        this.morphDom(componentEl, newEl);
                        this.mountComponent(componentEl);
                    }
                }
            } catch (err) {
                console.error('Failed to dispatch Pulse action:', err);
            } finally {
                loadingElements.forEach(l => l.classList.remove('loading-active'));
            }
        }

        // ==========================================
        // 3. Fast DOM Morphing Engine
        // ==========================================
        morphDom(fromNode, toNode) {
            if (!fromNode || !toNode) return;

            if (fromNode.nodeType !== toNode.nodeType || fromNode.nodeName !== toNode.nodeName) {
                fromNode.parentNode?.replaceChild(toNode.cloneNode(true), fromNode);
                return;
            }

            if (fromNode.nodeType === Node.TEXT_NODE) {
                if (fromNode.nodeValue !== toNode.nodeValue) {
                    fromNode.nodeValue = toNode.nodeValue;
                }
                return;
            }

            const fromAttrs = fromNode.attributes;
            const toAttrs = toNode.attributes;

            for (let i = 0; i < toAttrs.length; i++) {
                const attr = toAttrs[i];
                if (fromNode.getAttribute(attr.name) !== attr.value) {
                    fromNode.setAttribute(attr.name, attr.value);
                }
            }

            for (let i = fromAttrs.length - 1; i >= 0; i--) {
                const attr = fromAttrs[i];
                if (!toNode.hasAttribute(attr.name)) {
                    fromNode.removeAttribute(attr.name);
                }
            }

            if (fromNode.tagName === 'INPUT' || fromNode.tagName === 'TEXTAREA') {
                if (document.activeElement !== fromNode && fromNode.value !== toNode.value) {
                    fromNode.value = toNode.value;
                }
                if (fromNode.type === 'checkbox' || fromNode.type === 'radio') {
                    fromNode.checked = toNode.checked;
                }
            }

            const fromChildren = Array.from(fromNode.childNodes);
            const toChildren = Array.from(toNode.childNodes);

            const max = Math.max(fromChildren.length, toChildren.length);
            for (let i = 0; i < max; i++) {
                if (!fromChildren[i] && toChildren[i]) {
                    fromNode.appendChild(toChildren[i].cloneNode(true));
                } else if (fromChildren[i] && !toChildren[i]) {
                    fromNode.removeChild(fromChildren[i]);
                } else if (fromChildren[i] && toChildren[i]) {
                    this.morphDom(fromChildren[i], toChildren[i]);
                }
            }
        }
    }

    window.Pulse = new PulseRuntime();
})();
