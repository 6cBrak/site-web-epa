@php
    $chatAssistantName = \App\Models\Setting::get('chat_assistant_name', 'Aïcha');
@endphp
<style>
    .epa-chat-scroll { scrollbar-width: thin; scrollbar-color: #d1d5db transparent; }
    .epa-chat-scroll::-webkit-scrollbar { width: 6px; }
    .epa-chat-scroll::-webkit-scrollbar-track { background: transparent; }
    .epa-chat-scroll::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 9999px; }
    .epa-chat-scroll::-webkit-scrollbar-thumb:hover { background-color: #9ca3af; }
</style>
<div
    x-data="assistantWidget()"
    x-init="init()"
    x-on:open-assistant-chat.window="open = true; teaserVisible = false"
    class="fixed z-50 bottom-24 right-6 flex flex-col items-end"
>
    {{-- Bouton flottant + bulle de relance --}}
    <div class="relative">
        <div
            x-show="teaserVisible"
            x-cloak
            x-transition
            @click="open = true; teaserVisible = false"
            class="absolute right-full top-1/2 -translate-y-1/2 mr-3 flex items-center gap-2 bg-white text-epa-black text-sm pl-3 pr-2 py-2 rounded-lg shadow-lg border border-gray-100 whitespace-nowrap cursor-pointer"
        >
            <span x-text="teaserText"></span>
            <button
                type="button"
                @click.stop="teaserVisible = false"
                class="text-gray-400 hover:text-gray-600 shrink-0"
                aria-label="Fermer la suggestion"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <button
            type="button"
            @click="toggleOpen()"
            class="flex items-center justify-center w-14 h-14 rounded-full bg-epa-red text-white shadow-lg hover:opacity-90 transition"
            :aria-expanded="open"
            aria-label="Ouvrir le chat avec {{ $chatAssistantName }}"
        >
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm3.75 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.556-4.03 8.25-9 8.25a9.76 9.76 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
            </svg>
            <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Panneau de chat --}}
    <div
        x-show="open"
        x-cloak
        x-transition.opacity.duration.150ms
        @click.outside="open = false"
        class="fixed inset-0 sm:absolute sm:inset-auto sm:bottom-[4.5rem] sm:right-0 w-full h-full sm:w-[350px] sm:max-w-[88vw] sm:h-[500px] sm:max-h-[70vh] bg-white rounded-none sm:rounded-xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.25)] border-0 sm:border sm:border-gray-100 flex flex-col overflow-hidden"
    >
        {{-- Header --}}
        <div class="bg-epa-red text-white px-4 py-3 flex items-center justify-between gap-2 shrink-0">
            <span class="font-semibold text-sm">{{ $chatAssistantName }} · EPA_BURKINA</span>
            <div class="flex items-center gap-1.5">
                @if ($whatsappNumber)
                    <a :href="whatsappHref()" target="_blank" rel="noopener"
                       class="flex items-center justify-center w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 transition"
                       aria-label="Discuter directement sur WhatsApp" title="Discuter sur WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.39 1.26 4.81L2 22l5.42-1.36a9.9 9.9 0 0 0 4.62 1.14h.01c5.46 0 9.9-4.45 9.9-9.91S17.5 2 12.04 2Zm5.79 14.06c-.24.68-1.4 1.3-1.94 1.35-.5.05-1.02.24-3.4-.71-2.88-1.15-4.72-4.06-4.86-4.25-.14-.19-1.16-1.54-1.16-2.94s.73-2.09.99-2.38c.26-.28.56-.35.75-.35h.53c.17 0 .4-.06.62.48.24.58.81 1.99.88 2.13.07.14.12.31.02.5-.1.19-.15.31-.29.48-.14.17-.31.38-.44.51-.15.15-.3.31-.13.6.17.29.76 1.25 1.62 2.03 1.12 1 2.07 1.31 2.36 1.46.29.15.46.13.63-.08.17-.21.72-.84.91-1.13.19-.29.38-.24.63-.14.26.1 1.65.78 1.93.92.29.14.48.21.55.33.07.12.07.7-.17 1.38Z"/>
                        </svg>
                    </a>
                @endif
                <button
                    type="button"
                    @click="toggleOpen()"
                    class="flex items-center justify-center w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 transition"
                    aria-label="Fermer le chat"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div x-ref="scroller" class="epa-chat-scroll flex-1 overflow-y-auto px-3 py-3 space-y-2 bg-gray-50">
            <template x-for="(msg, index) in messages" :key="index">
                <div>
                    <div
                        :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start items-end gap-2'"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                    >
                        <template x-if="msg.role === 'assistant'">
                            <div class="w-6 h-6 rounded-full bg-epa-red text-white flex items-center justify-center shrink-0 text-[10px] font-bold">{{ mb_strtoupper(mb_substr($chatAssistantName, 0, 1)) }}</div>
                        </template>
                        <div
                            :class="msg.role === 'user'
                                ? 'bg-epa-red text-white rounded-2xl rounded-br-sm'
                                : 'bg-white text-epa-black border border-gray-200 rounded-2xl rounded-bl-sm'"
                            class="px-3 py-2 text-sm max-w-[80%] leading-relaxed"
                            x-html="render(msg.content)"
                        ></div>
                    </div>

                    {{-- Fiche recommandation --}}
                    <template x-if="msg.role === 'assistant' && msg.card">
                        <div class="ml-8 mt-2 bg-white border border-epa-red/30 rounded-xl shadow-sm max-w-[80%] overflow-hidden">
                            <img
                                x-show="msg.card.image"
                                :src="msg.card.image"
                                alt=""
                                class="w-full h-28 object-cover"
                            >
                            <div class="p-3">
                                <div class="flex items-center gap-1 text-[10px] font-bold text-epa-red uppercase tracking-wide mb-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3">
                                        <path d="M11.48 3.5a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.563.563 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.563.563 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                    </svg>
                                    <span>Recommandé pour vous</span>
                                </div>
                                <div class="font-semibold text-sm text-epa-black" x-text="msg.card.title"></div>
                                <div class="text-xs text-gray-600 mt-1" x-text="msg.card.reason"></div>
                                <div class="text-xs text-gray-500 mt-1.5 space-y-0.5">
                                    <div x-show="msg.card.antenne">📍 <span x-text="msg.card.antenne"></span></div>
                                    <div x-show="msg.card.next_session">🗓️ <span x-text="msg.card.next_session"></span></div>
                                </div>
                                <a
                                    :href="withKnownInfo(msg.card.slug ? ('/inscription?formation=' + msg.card.slug) : '/inscription')"
                                    class="mt-2.5 inline-flex items-center justify-center w-full px-3 py-2 rounded-md bg-epa-red text-white text-xs font-semibold hover:opacity-90 transition"
                                >
                                    S'inscrire à cette formation
                                </a>
                            </div>
                        </div>
                    </template>

                    {{-- Suggestions contextuelles (uniquement sur le dernier message) --}}
                    <template x-if="msg.role === 'assistant' && msg.quickReplies && msg.quickReplies.length && index === messages.length - 1 && !loading">
                        <div class="ml-8 mt-2 flex flex-wrap gap-2">
                            <template x-for="qr in msg.quickReplies" :key="qr">
                                <button
                                    type="button"
                                    @click="sendSuggestion(qr)"
                                    class="text-xs px-3 py-1.5 rounded-full border border-epa-red text-epa-red hover:bg-epa-red hover:text-white transition"
                                    x-text="qr"
                                ></button>
                            </template>
                        </div>
                    </template>
                </div>
            </template>

            <div x-show="messages.length === 1" class="flex flex-wrap gap-2 pt-1">
                <template x-for="s in suggestions" :key="s">
                    <button
                        type="button"
                        @click="sendSuggestion(s)"
                        class="text-xs px-3 py-1.5 rounded-full border border-epa-red text-epa-red hover:bg-epa-red hover:text-white transition"
                        x-text="s"
                    ></button>
                </template>
            </div>

            <div x-show="loading" class="flex justify-start items-end gap-2">
                <div class="w-6 h-6 rounded-full bg-epa-red text-white flex items-center justify-center shrink-0 text-[10px] font-bold">{{ mb_strtoupper(mb_substr($chatAssistantName, 0, 1)) }}</div>
                <div class="bg-white border border-gray-200 rounded-2xl rounded-bl-sm px-3 py-2.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce [animation-delay:-0.3s]"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce [animation-delay:-0.15s]"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce"></span>
                </div>
            </div>
        </div>

        {{-- Saisie --}}
        <form @submit.prevent="send()" class="border-t border-gray-100 p-2 flex items-center gap-2 shrink-0 bg-white">
            <input
                type="text"
                x-model="input"
                :disabled="loading"
                placeholder="Posez votre question…"
                class="flex-1 rounded-md border-gray-300 text-sm focus:border-epa-red focus:ring-epa-red"
            >
            <button
                type="submit"
                :disabled="loading || !input.trim()"
                class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-epa-red text-white disabled:opacity-40 hover:opacity-90 transition shrink-0"
                aria-label="Envoyer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.126A59.77 59.77 0 0 1 21.485 12 59.77 59.77 0 0 1 3.269 20.874L6 12Zm0 0h7.5" />
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    function assistantWidget() {
        return {
            open: false,
            sessionId: null,
            input: '',
            loading: false,
            teaserVisible: false,
            teaserText: 'Une question sur nos formations ? 👋',
            knownLead: null,
            exitPromptShown: false,
            messages: [
                { role: 'assistant', content: "Bonjour ! Je suis " + {!! \Illuminate\Support\Js::from($chatAssistantName) !!} + ", conseillère chez EPA_BURKINA 😊 Posez-moi vos questions sur nos formations, nos prix ou nos antennes." },
            ],
            suggestions: [
                'Voir les formations disponibles',
                'Quelle antenne est la plus proche de moi ?',
                'Quels sont les prix ?',
                'Comment je m\'inscris ?',
            ],

            async init() {
                this.$watch('open', (value) => {
                    if (value) this.scrollToBottom();
                });

                const storedSessionId = this.loadStoredSessionId();
                let returning = false;

                if (storedSessionId) {
                    this.sessionId = storedSessionId;
                    returning = await this.loadHistory();
                } else {
                    this.sessionId = (crypto.randomUUID ? crypto.randomUUID() : this.fallbackUuid());
                    this.persistSession();
                }

                setTimeout(() => {
                    if (this.open) return;
                    this.teaserVisible = true;
                    setTimeout(() => { this.teaserVisible = false; }, 10000);
                }, returning ? 500 : 4000);
            },

            loadStoredSessionId() {
                try {
                    const raw = localStorage.getItem('epa_assistant_session');
                    if (!raw) return null;

                    const data = JSON.parse(raw);
                    const expiryMs = 30 * 24 * 60 * 60 * 1000;

                    if (!data.sessionId || !data.lastActiveAt || (Date.now() - data.lastActiveAt) > expiryMs) {
                        return null;
                    }

                    return data.sessionId;
                } catch (e) {
                    return null;
                }
            },

            persistSession() {
                try {
                    localStorage.setItem('epa_assistant_session', JSON.stringify({
                        sessionId: this.sessionId,
                        lastActiveAt: Date.now(),
                    }));
                } catch (e) {
                    // localStorage indisponible (navigation privée, quota...) : la conversation reste en mémoire pour cette visite.
                }
            },

            async loadHistory() {
                try {
                    const response = await fetch('/api/assistant/history?session_id=' + encodeURIComponent(this.sessionId));
                    const data = await response.json();

                    if (data.messages && data.messages.length) {
                        this.messages = data.messages;

                        if (data.visitor_name) {
                            this.knownLead = { name: data.visitor_name, contact: data.visitor_contact || '' };
                        }

                        const topic = data.last_interest || this.lastMeaningfulUserMessage();
                        const greeting = data.visitor_name ? `Contente de vous revoir, ${data.visitor_name} !` : 'Contente de vous revoir !';

                        this.messages.push({
                            role: 'assistant',
                            content: topic
                                ? `${greeting} 👋 La dernière fois, on parlait de : « ${topic} ». On continue sur ce sujet, ou autre chose aujourd'hui ?`
                                : `${greeting} 👋 Comment puis-je vous aider aujourd'hui ?`,
                        });
                        this.teaserText = data.visitor_name
                            ? `Bon retour, ${data.visitor_name} ! 👋`
                            : 'Contente de vous revoir ! 👋';

                        return true;
                    }
                } catch (e) {
                    // échec silencieux : on garde le message d'accueil par défaut
                } finally {
                    this.scrollToBottom();
                }

                return false;
            },

            sendSuggestion(text) {
                this.input = text;
                this.send();
            },

            lastMeaningfulUserMessage() {
                const candidate = [...this.messages].reverse().find((m) => m.role === 'user' && m.content && m.content.trim().length > 8);

                if (!candidate) return null;

                const text = candidate.content.trim();

                return text.length > 70 ? text.slice(0, 70).trim() + '…' : text;
            },

            toggleOpen() {
                this.teaserVisible = false;

                if (this.open) {
                    const hasSubstantialInterest = this.messages.length >= 4 && !this.knownLead;

                    if (hasSubstantialInterest && !this.exitPromptShown) {
                        this.exitPromptShown = true;
                        this.messages.push({
                            role: 'assistant',
                            content: "Avant que tu partes 😊 Peux-tu me laisser ton prénom et un contact (téléphone ou email) ? Je te ferai suivre plus d'infos sans que tu aies à revenir chercher.",
                        });
                        this.scrollToBottom();
                        return;
                    }
                }

                this.open = !this.open;
            },

            whatsappHref() {
                const base = 'https://wa.me/{{ $whatsappNumber }}';
                const lastUserMessage = [...this.messages].reverse().find((m) => m.role === 'user');

                if (!lastUserMessage) return base;

                const text = `Bonjour, je viens de discuter avec l'assistant du site EPA à propos de : "${lastUserMessage.content.slice(0, 200)}"`;

                return base + '?text=' + encodeURIComponent(text);
            },

            withKnownInfo(url) {
                if (!url.startsWith('/inscription') || !this.knownLead || !this.knownLead.name) {
                    return url;
                }

                const [base, query] = url.split('?');
                const params = new URLSearchParams(query || '');
                const parts = this.knownLead.name.trim().split(/\s+/);
                const firstName = parts.shift() || '';
                const lastName = parts.join(' ');

                if (firstName) params.set('first_name', firstName);
                if (lastName) params.set('last_name', lastName);

                if (this.knownLead.contact) {
                    if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.knownLead.contact)) {
                        params.set('email', this.knownLead.contact);
                    } else {
                        params.set('phone', this.knownLead.contact);
                    }
                }

                return base + '?' + params.toString();
            },

            render(content) {
                const escaper = document.createElement('div');
                escaper.textContent = content;
                let safe = escaper.innerHTML;

                safe = safe.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');

                safe = safe.replace(
                    /\[([^\]]+)\]\((\/(?!\/)[^\s)]*|https:\/\/wa\.me\/[^\s)]+)\)/g,
                    (match, label, url) => {
                        const external = url.startsWith('https://');
                        const attrs = external ? ' target="_blank" rel="noopener"' : '';
                        return `<a href="${this.withKnownInfo(url)}" class="underline font-semibold text-epa-red hover:opacity-80"${attrs}>${label}</a>`;
                    }
                );

                return this.renderLines(safe);
            },

            renderLines(text) {
                const lines = text.split('\n');
                let html = '';
                let inList = false;

                for (const line of lines) {
                    const item = line.match(/^-\s+(.+)/);

                    if (item) {
                        if (!inList) { html += '<ul class="list-disc pl-4 my-1 space-y-0.5">'; inList = true; }
                        html += `<li>${item[1]}</li>`;
                        continue;
                    }

                    if (inList) { html += '</ul>'; inList = false; }
                    html += (html ? '<br>' : '') + line;
                }

                if (inList) html += '</ul>';

                return html;
            },

            fallbackUuid() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                    const r = Math.random() * 16 | 0;
                    const v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            },

            async send() {
                const text = this.input.trim();
                if (!text || this.loading) return;

                this.messages.push({ role: 'user', content: text });
                this.input = '';
                this.loading = true;
                this.persistSession();
                this.scrollToBottom();

                let assistantIndex = null;

                const ensureAssistantMessage = () => {
                    if (assistantIndex === null) {
                        this.loading = false;
                        this.messages.push({ role: 'assistant', content: '', quickReplies: [], card: null });
                        assistantIndex = this.messages.length - 1;
                    }
                };

                try {
                    const response = await fetch('/api/assistant/message', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'text/event-stream' },
                        body: JSON.stringify({ session_id: this.sessionId, message: text }),
                    });

                    if (!response.ok || !response.body) {
                        throw new Error('invalid response');
                    }

                    const reader = response.body.getReader();
                    const decoder = new TextDecoder();
                    let buffer = '';

                    while (true) {
                        const { value, done } = await reader.read();
                        if (done) break;

                        buffer += decoder.decode(value, { stream: true });
                        const events = buffer.split('\n\n');
                        buffer = events.pop();

                        for (const rawEvent of events) {
                            if (!rawEvent.trim()) continue;

                            let eventType = 'message';
                            let dataLine = '';

                            for (const line of rawEvent.split('\n')) {
                                if (line.startsWith('event:')) eventType = line.slice(6).trim();
                                else if (line.startsWith('data:')) dataLine += line.slice(5).trim();
                            }

                            if (!dataLine) continue;

                            let payload;
                            try { payload = JSON.parse(dataLine); } catch (e) { continue; }

                            if (eventType === 'delta' && payload.text) {
                                ensureAssistantMessage();
                                this.messages[assistantIndex].content += payload.text;
                                this.scrollToBottom();
                            } else if (eventType === 'done') {
                                ensureAssistantMessage();
                                if (!this.messages[assistantIndex].content) {
                                    this.messages[assistantIndex].content = "D'accord !";
                                }
                                this.messages[assistantIndex].quickReplies = payload.quick_replies || [];
                                this.messages[assistantIndex].card = payload.card || null;
                                if (payload.lead) {
                                    this.knownLead = payload.lead;
                                }
                            } else if (eventType === 'error') {
                                ensureAssistantMessage();
                                this.messages[assistantIndex].content = payload.message || "Désolé, une erreur est survenue.";
                            }
                        }
                    }
                } catch (e) {
                    ensureAssistantMessage();
                    this.messages[assistantIndex].content = this.messages[assistantIndex].content
                        || "Désolé, une erreur est survenue. Réessayez ou contactez-nous via WhatsApp.";
                } finally {
                    this.loading = false;
                    this.scrollToBottom();
                }
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const el = this.$refs.scroller;
                    if (el) el.scrollTop = el.scrollHeight;
                });
            },
        };
    }
</script>
