export const dynamic = 'force-dynamic';

import React from 'react';
import {
    BookOpen,
    UserPlus,
    Building2,
    FileText,
    ShieldCheck,
    Smartphone,
    ArrowRight,
    CheckCircle2
} from 'lucide-react';

export default function ManualPage() {
    return (
        <div className="p-8 max-w-5xl mx-auto text-slate-200">
            <div className="mb-12 text-center">
                <div className="inline-flex items-center justify-center p-4 bg-blue-600/20 rounded-full mb-6">
                    <BookOpen className="w-12 h-12 text-blue-500" />
                </div>
                <h1 className="text-4xl font-bold text-white mb-4">Como Usar o Seu Novo Sistema</h1>
                <p className="text-lg text-slate-400 max-w-2xl mx-auto">
                    Um guia passo a passo, super simples, para você dominar o JCA ERP em 5 minutos.
                </p>
            </div>

            <div className="space-y-12">
                {/* Passo 1: Cadastro de Empresa */}
                <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 relative overflow-hidden">
                    <div className="absolute top-0 right-0 p-4 opacity-10">
                        <Building2 className="w-64 h-64" />
                    </div>
                    <div className="relative z-10">
                        <div className="flex items-center gap-4 mb-6">
                            <div className="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xl font-bold">1</div>
                            <h2 className="text-2xl font-bold text-white">Cadastrando uma Empresa</h2>
                        </div>
                        <div className="pl-16 space-y-4">
                            <p className="text-lg mb-4">
                                Tudo começa aqui. Antes de criar usuários ou lançar impostos, o sistema precisa saber que a empresa existe.
                            </p>
                            <ul className="space-y-3">
                                <li className="flex items-start gap-3">
                                    <CheckCircle2 className="w-5 h-5 text-emerald-500 mt-1 shrink-0" />
                                    <span>Vá no menu <strong>Clientes</strong>.</span>
                                </li>
                                <li className="flex items-start gap-3">
                                    <CheckCircle2 className="w-5 h-5 text-emerald-500 mt-1 shrink-0" />
                                    <span>Preencha o <strong>Nome</strong>, <strong>CNPJ</strong> e o <strong>Regime Tributário</strong> (Simples, Presumido, etc).</span>
                                </li>
                                <li className="flex items-start gap-3">
                                    <CheckCircle2 className="w-5 h-5 text-emerald-500 mt-1 shrink-0" />
                                    <span>Clique em <strong>Salvar Cliente</strong>.</span>
                                </li>
                            </ul>
                            <div className="bg-blue-500/10 border border-blue-500/20 p-4 rounded-xl mt-6">
                                <span className="font-bold text-blue-400">💡 Dica:</span> Ao criar uma empresa, o sistema já cria automaticamente as tarefas mensais dela (Fiscal, RH e Contábil).
                            </div>
                        </div>
                    </div>
                </div>

                {/* Passo 2: Criar Acesso para o Cliente */}
                <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 relative overflow-hidden">
                    <div className="absolute top-0 right-0 p-4 opacity-10">
                        <UserPlus className="w-64 h-64" />
                    </div>
                    <div className="relative z-10">
                        <div className="flex items-center gap-4 mb-6">
                            <div className="w-12 h-12 rounded-xl bg-purple-600 text-white flex items-center justify-center text-xl font-bold">2</div>
                            <h2 className="text-2xl font-bold text-white">Dando Acesso ao Cliente</h2>
                        </div>
                        <div className="pl-16 space-y-4">
                            <p className="text-lg mb-4">
                                Agora que a empresa existe, você precisa criar o login para o seu cliente (o dono da empresa) poder acessar o portal dele.
                            </p>
                            <ul className="space-y-3">
                                <li className="flex items-start gap-3">
                                    <CheckCircle2 className="w-5 h-5 text-emerald-500 mt-1 shrink-0" />
                                    <span>Vá no menu <strong>Usuários</strong> e clique em &quot;Novo Usuário&quot;.</span>
                                </li>
                                <li className="flex items-start gap-3">
                                    <CheckCircle2 className="w-5 h-5 text-emerald-500 mt-1 shrink-0" />
                                    <span>Preencha Nome, Email e Senha.</span>
                                </li>
                                <li className="flex items-start gap-3">
                                    <CheckCircle2 className="w-5 h-5 text-emerald-500 mt-1 shrink-0" />
                                    <span>Em &quot;Tipo de Acesso&quot;, selecione <strong>CLIENTE</strong>.</span>
                                </li>
                                <li className="flex items-start gap-3">
                                    <CheckCircle2 className="w-5 h-5 text-emerald-500 mt-1 shrink-0" />
                                    <span><strong>Importante:</strong> Em &quot;Vincular à Empresa&quot;, selecione a empresa que você criou no Passo 1.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {/* Passo 3: Enviando Documentos e Guias */}
                <div className="bg-slate-900/40 border border-slate-800 rounded-3xl p-8 relative overflow-hidden">
                    <div className="absolute top-0 right-0 p-4 opacity-10">
                        <FileText className="w-64 h-64" />
                    </div>
                    <div className="relative z-10">
                        <div className="flex items-center gap-4 mb-6">
                            <div className="w-12 h-12 rounded-xl bg-pink-600 text-white flex items-center justify-center text-xl font-bold">3</div>
                            <h2 className="text-2xl font-bold text-white">Enviando Guias e Impostos</h2>
                        </div>
                        <div className="pl-16 space-y-4">
                            <p className="text-lg mb-4">
                                É aqui que a mágica acontece. Você lança a guia aqui, e ela aparece automaticamente no celular do seu cliente.
                            </p>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
                                    <h4 className="font-bold text-white mb-2 flex items-center gap-2">
                                        <ArrowRight className="w-4 h-4 text-pink-500" />
                                        Fiscal (Impostos)
                                    </h4>
                                    <p className="text-sm text-slate-400">Vá em <strong>Fiscal</strong> &gt; &quot;Nova Guia&quot;. Cadastre o valor e o vencimento do DAS, ICMS, etc.</p>
                                </div>
                                <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
                                    <h4 className="font-bold text-white mb-2 flex items-center gap-2">
                                        <ArrowRight className="w-4 h-4 text-pink-500" />
                                        Departamento Pessoal
                                    </h4>
                                    <p className="text-sm text-slate-400">Vá em <strong>Folha / DP</strong>. Aqui você cadastra os funcionários e controla férias e salários.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Passo 4: O Que o Cliente Vê? */}
                <div className="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white relative overflow-hidden">
                    <div className="absolute top-0 right-0 p-4 opacity-10">
                        <Smartphone className="w-64 h-64" />
                    </div>
                    <div className="relative z-10">
                        <div className="flex items-center gap-4 mb-6">
                            <div className="w-12 h-12 rounded-xl bg-white text-blue-600 flex items-center justify-center text-xl font-bold">4</div>
                            <h2 className="text-2xl font-bold">A Visão do Cliente</h2>
                        </div>
                        <div className="pl-16 space-y-4">
                            <p className="text-lg leading-relaxed mb-4">
                                Quando o seu cliente logar (usando o email/senha que você criou no Passo 2), ele verá um Painel Bonito com:
                            </p>
                            <ul className="space-y-2 text-blue-100">
                                <li className="flex items-center gap-2">✓ As guias que você lançou (com botão para baixar).</li>
                                <li className="flex items-center gap-2">✓ Avisos de &quot;Pendente&quot; ou &quot;Pago&quot;.</li>
                                <li className="flex items-center gap-2">✓ Opção de abrir chamados (Solicitações) para você.</li>
                                <li className="flex items-center gap-2">✓ Upload de documentos para te enviar (notas, extratos).</li>
                            </ul>
                            <div className="mt-6 pt-6 border-t border-white/20">
                                <h3 className="font-bold flex items-center gap-2 mb-2">
                                    <ShieldCheck className="w-5 h-5" />
                                    Protocolo Automático
                                </h3>
                                <p className="text-sm opacity-90">
                                    Tudo o que você envia ou recebe gera um <strong>Protocolo</strong> digital. Isso é a sua segurança jurídica de que o documento foi entregue. Você pode ver tudo no menu &quot;Protocolos&quot;.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="text-center pt-8 border-t border-slate-800">
                    <p className="text-slate-500 italic">
                        Pronto! Você já sabe operar 100% do sistema. Simples assim. 🚀
                    </p>
                </div>
            </div>
        </div>
    );
}
