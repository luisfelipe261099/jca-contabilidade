import React from 'react';
import { Mail, MapPin, Phone, MessageCircle } from 'lucide-react';

export default function Contact() {
    return (
        <section id="contato" className="py-24 px-6 bg-slate-950 border-t border-slate-900">
            <div className="max-w-7xl mx-auto">
                <div className="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest mb-6">
                            Fale Conosco
                        </div>
                        <h2 className="text-4xl md:text-5xl font-bold text-white mb-6">
                            Pronto para evoluir a <br />
                            <span className="text-emerald-500">gestão do seu negócio?</span>
                        </h2>
                        <p className="text-slate-400 text-lg mb-10 leading-relaxed">
                            Não perca tempo com dúvidas. Entre em contato agora e descubra como a JCA pode customizar a solução perfeita para sua empresa.
                        </p>

                        <div className="space-y-6">
                            <div className="flex items-start gap-4">
                                <div className="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-blue-500 shrink-0">
                                    <MapPin className="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 className="text-white font-bold mb-1">Localização</h4>
                                    <p className="text-slate-400">Atendimento em todo Brasil</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <div className="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-blue-500 shrink-0">
                                    <Mail className="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 className="text-white font-bold mb-1">Email</h4>
                                    <p className="text-slate-400">contato@jcasolucoes.com.br</p>
                                </div>
                            </div>

                            <div className="flex items-start gap-4">
                                <div className="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-blue-500 shrink-0">
                                    <Phone className="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 className="text-white font-bold mb-1">Telefone</h4>
                                    <p className="text-slate-400">(XX) XXXX-XXXX</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-slate-900/50 p-8 rounded-3xl border border-slate-800">
                        <h3 className="text-2xl font-bold text-white mb-6">Mande uma mensagem</h3>
                        <form className="space-y-4">
                            <div className="grid md:grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <label className="text-sm font-medium text-slate-300">Nome</label>
                                    <input type="text" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="Vitor Oliveira" />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-sm font-medium text-slate-300">Telefone</label>
                                    <input type="text" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="(11) 99999-9999" />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-medium text-slate-300">Email</label>
                                <input type="email" className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors" placeholder="vitor@empresa.com" />
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-medium text-slate-300">Como podemos ajudar?</label>
                                <textarea className="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors h-32" placeholder="Gostaria de saber mais sobre..." />
                            </div>

                            <button className="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2">
                                Enviar Mensagem
                                <ArrowRightIcon className="w-5 h-5" />
                            </button>

                            <div className="relative flex py-2 items-center">
                                <div className="flex-grow border-t border-slate-800"></div>
                                <span className="flex-shrink-0 mx-4 text-slate-500 text-sm">Ou chame no WhatsApp</span>
                                <div className="flex-grow border-t border-slate-800"></div>
                            </div>

                            <button type="button" className="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
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
