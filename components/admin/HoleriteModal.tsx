'use client';

import React from 'react';
import { X, Printer } from 'lucide-react';

interface Employee {
    id: string;
    name: string;
    role: string;
    salary: number;
    client: { name: string; cnpj?: string };
    admissionDate: string;
}

interface HoleriteModalProps {
    employee: Employee | null;
    isOpen: boolean;
    onClose: () => void;
}

export default function HoleriteModal({ employee, isOpen, onClose }: HoleriteModalProps) {
    if (!isOpen || !employee) return null;

    // Constantes e cálculos simplificados (para demonstração)
    const inss = employee.salary * 0.09; // Aprox 9% como demonstrativo
    const baseCalculoIR = employee.salary - inss;
    const irrf = baseCalculoIR > 2112 ? (baseCalculoIR - 2112) * 0.075 : 0; // Exemplo didático
    const liquido = employee.salary - inss - irrf;

    const formatCurrency = (val: number) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm print:bg-white print:p-0">
            <div className="bg-white rounded-xl w-full max-w-4xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh] print:max-h-none print:shadow-none print:w-full print:border-none">

                {/* Cabeçalho de Controle (Não impresso) */}
                <div className="p-4 border-b border-slate-200 flex items-center justify-between bg-slate-100 no-print">
                    <h2 className="text-xl font-bold text-slate-800">Visualização de Holerite</h2>
                    <div className="flex gap-2">
                        <button
                            onClick={() => window.print()}
                            className="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold transition-all text-sm shadow-md"
                        >
                            <Printer className="w-4 h-4" /> Exportar / Imprimir PDF
                        </button>
                        <button onClick={onClose} className="p-2 hover:bg-slate-200 rounded-lg transition-colors text-slate-500">
                            <X className="w-5 h-5" />
                        </button>
                    </div>
                </div>

                {/* Área do Holerite (Vai para a impressora) */}
                <div className="p-8 overflow-y-auto custom-scrollbar flex-1 text-black bg-white print:p-0">
                    <div className="border border-black p-1 print:border-none">
                        {/* Cabecalho da Empresa */}
                        <div className="border-b border-black p-4 flex justify-between items-start">
                            <div>
                                <h1 className="font-bold text-xl uppercase tracking-tighter">{employee.client.name}</h1>
                                <p className="text-sm font-mono mt-1">CNPJ: {employee.client.cnpj || '00.000.000/0001-00'}</p>
                            </div>
                            <div className="text-right">
                                <h2 className="font-bold text-lg uppercase">Recibo de Pagamento de Salário</h2>
                                <p className="text-sm mt-1">Referência: <span className="font-bold">{new Date().toLocaleDateString('pt-BR', { month: '2-digit', year: 'numeric' })}</span></p>
                            </div>
                        </div>

                        {/* Dados do Funcionario */}
                        <div className="border-b border-black p-4 grid grid-cols-4 gap-4 text-sm bg-slate-50 print:bg-transparent">
                            <div className="col-span-2">
                                <p className="text-gray-600 text-[10px] font-bold uppercase tracking-widest">Código / Nome do Funcionário</p>
                                <p className="font-semibold uppercase mt-1">001 - {employee.name}</p>
                            </div>
                            <div>
                                <p className="text-gray-600 text-[10px] font-bold uppercase tracking-widest">CBO / Cargo</p>
                                <p className="font-semibold uppercase mt-1">{employee.role}</p>
                            </div>
                            <div>
                                <p className="text-gray-600 text-[10px] font-bold uppercase tracking-widest">Admissão</p>
                                <p className="font-semibold mt-1">{new Date(employee.admissionDate).toLocaleDateString('pt-BR')}</p>
                            </div>
                        </div>

                        {/* Tabela de Vencimentos */}
                        <table className="w-full text-sm text-left border-collapse min-h-[350px]">
                            <thead>
                                <tr className="border-b border-black bg-slate-100 print:bg-transparent">
                                    <th className="p-2 border-r border-black w-16 text-center text-xs">Cód.</th>
                                    <th className="p-2 border-r border-black text-xs">Descrição</th>
                                    <th className="p-2 border-r border-black w-24 text-center text-xs">Ref.</th>
                                    <th className="p-2 border-r border-black w-32 text-right text-xs">Vencimentos</th>
                                    <th className="p-2 w-32 text-right text-xs">Descontos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr className="align-top">
                                    <td className="p-2 px-4 border-r border-black text-center font-mono text-xs">001</td>
                                    <td className="p-2 px-4 border-r border-black font-semibold">Salário Base</td>
                                    <td className="p-2 px-4 border-r border-black text-center text-xs text-gray-600">30,00</td>
                                    <td className="p-2 px-4 border-r border-black text-right">{formatCurrency(employee.salary)}</td>
                                    <td className="p-2 px-4 text-right"></td>
                                </tr>
                                <tr className="align-top">
                                    <td className="p-2 px-4 border-r border-black text-center font-mono text-xs">075</td>
                                    <td className="p-2 px-4 border-r border-black text-red-700">INSS S/ Salário</td>
                                    <td className="p-2 px-4 border-r border-black text-center text-xs text-gray-600">9.00%</td>
                                    <td className="p-2 px-4 border-r border-black text-right"></td>
                                    <td className="p-2 px-4 text-right">{formatCurrency(inss)}</td>
                                </tr>
                                <tr className="align-top">
                                    <td className="p-2 px-4 border-r border-black text-center font-mono text-xs">082</td>
                                    <td className="p-2 px-4 border-r border-black text-red-700">IRRF S/ Salário</td>
                                    <td className="p-2 px-4 border-r border-black text-center text-xs text-gray-600">7.50%</td>
                                    <td className="p-2 px-4 border-r border-black text-right"></td>
                                    <td className="p-2 px-4 text-right">{formatCurrency(irrf)}</td>
                                </tr>
                                {/* Linha para expandir a tabela visualmente */}
                                <tr>
                                    <td className="border-r border-black h-[220px]"></td>
                                    <td className="border-r border-black"></td>
                                    <td className="border-r border-black"></td>
                                    <td className="border-r border-black"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                        {/* Totais das Colunas de Vencimento/Desconto */}
                        <div className="border-t border-b border-black flex text-sm text-gray-700 bg-slate-50 print:bg-transparent">
                            <div className="flex-1 p-2 border-r border-black text-right text-xs uppercase font-bold tracking-widest pt-3">Totais</div>
                            <div className="w-32 p-2 border-r border-black text-right font-bold text-black">{formatCurrency(employee.salary)}</div>
                            <div className="w-32 p-2 text-right font-bold text-black">{formatCurrency(inss + irrf)}</div>
                        </div>

                        {/* Rodapé e Liquido */}
                        <div className="flex">
                            <div className="flex-1 p-4 border-r border-black flex flex-col justify-between pt-6">
                                <div className="grid grid-cols-3 gap-2 text-[10px] text-center mb-6">
                                    <div className="border border-gray-300 p-2 rounded">
                                        <div className="text-gray-500 uppercase">Salário Base</div>
                                        <div className="font-bold mt-1 text-black">{formatCurrency(employee.salary)}</div>
                                    </div>
                                    <div className="border border-gray-300 p-2 rounded">
                                        <div className="text-gray-500 uppercase">Base Cálc INSS</div>
                                        <div className="font-bold mt-1 text-black">{formatCurrency(employee.salary)}</div>
                                    </div>
                                    <div className="border border-gray-300 p-2 rounded">
                                        <div className="text-gray-500 uppercase">Base Cálc FGTS</div>
                                        <div className="font-bold mt-1 text-black">{formatCurrency(employee.salary)}</div>
                                    </div>
                                </div>
                                <p className="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Avisos</p>
                                <p className="text-xs uppercase text-gray-700">Depósito FGTS processado em tempo hábil.</p>
                            </div>
                            <div className="w-64 flex flex-col text-right">
                                <div className="p-6 bg-slate-100 print:bg-transparent flex-1 flex flex-col justify-center border-b border-gray-300">
                                    <p className="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Valor Líquido</p>
                                    <p className="text-2xl font-black text-blue-900 print:text-black">
                                        ► {formatCurrency(liquido)}
                                    </p>
                                </div>
                                <div className="p-4 pt-8 text-center bg-white">
                                    <div className="border-t border-black w-full border-dashed mx-auto pt-2">
                                        <p className="text-[10px] tracking-widest uppercase">Assinatura Funcinário</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
