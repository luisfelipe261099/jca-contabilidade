import React from 'react';
import { Mail, MapPin, Phone, MessageCircle } from 'lucide-react';

export default function Contact() {
    return (
        <section id="contato" className="py-16 md:py-24 px-6">
            <div className="max-w-7xl mx-auto">
                <div className="grid lg:grid-cols-2 gap-12 md:gap-16 items-center">
                    <div className="reveal-up">
                        <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-[#48BFB0]/35 text-[#2B9F90] text-[11px] font-extrabold uppercase tracking-[0.14em] mb-6">
                            Fale conosco
                        </div>
                        <h2 className="text-3xl md:text-5xl font-black text-[#0B2F47] mb-6 leading-tight">
                            Pronta para transformar
                            <span className="block text-[#2B9F90]">a gestao do seu negocio?</span>
                        </h2>
                        <p className="text-[#476579] text-base md:text-lg mb-10 leading-relaxed font-semibold">
                            Converse com a Jade Cristina Contabilidade e receba um plano sob medida para o seu momento.
                        </p>

                        <div className="space-y-6">
                            <div className="flex items-start gap-4">
                                <div className="w-12 h-12 bg-white border border-sky-100 rounded-xl flex items-center justify-center text-[#007CC3] shrink-0">
                                    <MapPin className="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 className="text-[#0B2F47] font-black mb-1">Localizacao</h4>
                                    <p className="text-[#547084] font-semibold">Curitiba - PR</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <div className="w-12 h-12 bg-white border border-sky-100 rounded-xl flex items-center justify-center text-[#007CC3] shrink-0">
                                    <Mail className="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 className="text-[#0B2F47] font-black mb-1">Email</h4>
                                    <p className="text-[#547084] font-semibold">jadycris@yahoo.com.br</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <div className="w-12 h-12 bg-white border border-sky-100 rounded-xl flex items-center justify-center text-[#007CC3] shrink-0">
                                    <Phone className="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 className="text-[#0B2F47] font-black mb-1">Telefone / WhatsApp</h4>
                                    <p className="text-[#547084] font-semibold">(41) 98858-4456</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="brand-card p-8 reveal-up" style={{ animationDelay: '120ms' }}>
                        <h3 className="text-2xl font-black text-[#0B2F47] mb-6">Mande uma mensagem</h3>
                        <form className="space-y-4">
                            <div className="grid md:grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-[#3F6074]">Nome</label>
                                    <input type="text" className="w-full bg-white border border-sky-100 rounded-xl px-4 py-3 text-[#0B2F47] focus:outline-none focus:border-[#007CC3] transition-colors" placeholder="Seu nome" />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-bold text-[#3F6074]">Telefone</label>
                                    <input type="text" className="w-full bg-white border border-sky-100 rounded-xl px-4 py-3 text-[#0B2F47] focus:outline-none focus:border-[#007CC3] transition-colors" placeholder="(41) 99999-9999" />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-bold text-[#3F6074]">Email</label>
                                <input type="email" className="w-full bg-white border border-sky-100 rounded-xl px-4 py-3 text-[#0B2F47] focus:outline-none focus:border-[#007CC3] transition-colors" placeholder="voce@empresa.com" />
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-bold text-[#3F6074]">Como podemos ajudar?</label>
                                <textarea className="w-full bg-white border border-sky-100 rounded-xl px-4 py-3 text-[#0B2F47] focus:outline-none focus:border-[#007CC3] transition-colors h-32" placeholder="Conte o que sua empresa precisa hoje" />
                            </div>

                            <button className="w-full bg-gradient-to-r from-[#007CC3] to-[#33B8AE] text-white font-extrabold py-4 rounded-xl transition-all shadow-[0_12px_24px_rgba(0,124,195,0.28)] flex items-center justify-center gap-2">
                                Enviar Mensagem
                                <ArrowRightIcon className="w-5 h-5" />
                            </button>

                            <div className="relative flex py-2 items-center">
                                <div className="flex-grow border-t border-sky-100"></div>
                                <span className="flex-shrink-0 mx-4 text-[#5E7789] text-sm font-bold">Ou chame no WhatsApp</span>
                                <div className="flex-grow border-t border-sky-100"></div>
                            </div>

                            <button type="button" className="w-full bg-[#25B49F] hover:bg-[#219D8C] text-white font-extrabold py-4 rounded-xl transition-all shadow-[0_10px_20px_rgba(37,180,159,0.25)] flex items-center justify-center gap-2">
                                <MessageCircle className="w-5 h-5" />
                                WhatsApp Direto
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    );
}

function ArrowRightIcon(props: any) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" {...props}>
            <path d="M5 12h14" />
            <path d="m12 5 7 7-7 7" />
        </svg>
    )
}
