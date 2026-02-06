const { PrismaClient } = require('@prisma/client');
const bcrypt = require('bcryptjs');
const prisma = new PrismaClient();

async function main() {
    console.log('🌱 Starting seed...');

    // Clean up existing data - Order matters due to foreign keys!
    await prisma.ticket.deleteMany();
    await prisma.financialRecord.deleteMany();
    await prisma.protocol.deleteMany();
    await prisma.legalProcess.deleteMany();
    await prisma.tax.deleteMany();
    await prisma.employee.deleteMany();
    await prisma.task.deleteMany();
    await prisma.document.deleteMany();
    await prisma.client.deleteMany();
    await prisma.user.deleteMany();

    // Hash passwords
    const adminPassword = await bcrypt.hash('admin', 10);
    const clientPassword = await bcrypt.hash('123', 10);

    // Create Admin User
    const admin = await prisma.user.create({
        data: {
            email: 'admin@example.com',
            name: 'Administrador JCA',
            password: adminPassword,
            role: 'ADMIN',
        },
    });

    console.log('✅ Admin user created');

    // --- Client 1: Panificadora do João LTDA (Simples Nacional, Commerce) ---
    const client1 = await prisma.client.create({
        data: {
            name: 'Panificadora do João LTDA',
            cnpj: '12.345.678/0001-90',
            regime: 'SIMPLES_NACIONAL',
            status: 'ATENDIMENTO',
            responsibleId: admin.id,
        },
    });

    // Create Login for Client 1
    await prisma.user.create({
        data: {
            email: 'joao@panificadora.com',
            name: 'João da Silva',
            password: clientPassword,
            role: 'CLIENT',
            clientId: client1.id,
        },
    });

    // Seed Data for Client 1
    // Taxes (Simples Nacional is the main one)
    await prisma.tax.createMany({
        data: [
            { name: 'DAS - Simples Nacional', amount: 1250.00, dueDate: new Date('2026-02-20'), status: 'PENDING', clientId: client1.id },
            { name: 'FGTS Mensal', amount: 480.00, dueDate: new Date('2026-02-07'), status: 'PAID', clientId: client1.id },
            { name: 'ICMS Antecipado', amount: 320.50, dueDate: new Date('2026-02-15'), status: 'PENDING', clientId: client1.id },
        ],
    });

    // Employees (Bakery Staff)
    await prisma.employee.createMany({
        data: [
            { name: 'Maria Oliveira', role: 'Gerente', admissionDate: new Date('2020-05-10'), salary: 3500.00, status: 'ACTIVE', clientId: client1.id },
            { name: 'Carlos Santos', role: 'Padeiro', admissionDate: new Date('2021-08-15'), salary: 2800.00, status: 'ACTIVE', clientId: client1.id },
            { name: 'Ana Souza', role: 'Atendente', admissionDate: new Date('2023-01-20'), salary: 1800.00, status: 'VACATION', clientId: client1.id },
            { name: 'Pedro Lima', role: 'Auxiliar', admissionDate: new Date('2023-06-01'), salary: 1600.00, status: 'ACTIVE', clientId: client1.id },
        ],
    });

    // Legal Processes (Routine updates)
    await prisma.legalProcess.createMany({
        data: [
            { title: 'Renovação Alvará Sanitário', description: 'Processo anual de renovação junto à prefeitura.', status: 'IN_PROGRESS', stage: 'Vistoria Agendada', clientId: client1.id },
        ],
    });

    // Protocols (Document delivery)
    await prisma.protocol.createMany({
        data: [
            { code: 'PRT-2026-001', type: 'Guia', description: 'Guia DAS 01/2026', receivedAt: new Date('2026-02-05T14:00:00Z'), clientId: client1.id },
            { code: 'PRT-2026-003', type: 'Relatório', description: 'Folha de Pagamento 01/2026', receivedAt: null, clientId: client1.id },
        ],
    });

    // Financial Records (JCA Billing)
    await prisma.financialRecord.createMany({
        data: [
            { description: 'Honorários Contábeis - Fev/2026', amount: 1500.00, type: 'INCOME', dueDate: new Date('2026-02-10'), status: 'PENDING', clientId: client1.id },
            { description: 'Taxa de Alteração Contratual', amount: 450.00, type: 'INCOME', dueDate: new Date('2026-01-15'), status: 'PAID', clientId: client1.id },
        ],
    });

    // Tickets
    await prisma.ticket.create({
        data: {
            title: 'Dúvida sobre Férias',
            description: 'Preciso saber qtos dias a Ana tem direito.',
            status: 'OPEN',
            priority: 'NORMAL',
            clientId: client1.id,
        },
    });

    // Valid Documents
    await prisma.document.createMany({
        data: [
            { name: 'Contrato Social.pdf', url: 'https://placehold.co/600x400', type: 'Contrato', clientId: client1.id },
            { name: 'Cartão CNPJ.pdf', url: 'https://placehold.co/600x400', type: 'Certidão', clientId: client1.id },
            { name: 'Balanço 2025.pdf', url: 'https://placehold.co/600x400', type: 'Relatório', clientId: client1.id },
        ]
    });

    // Tasks (Obrigações)
    await prisma.task.createMany({
        data: [
            { title: 'Envio de Notas Fiscais', description: 'Enviar notas do mês anterior', dueDate: new Date('2026-02-05'), completed: true, category: 'Fiscal', clientId: client1.id },
            { title: 'Pagamento DAS', description: 'Vencimento da guia do Simples', dueDate: new Date('2026-02-20'), completed: false, category: 'Financeiro', clientId: client1.id },
            { title: 'Assinar Recibos de Férias', description: 'Funcionária Ana Souza', dueDate: new Date('2026-02-15'), completed: false, category: 'DP', clientId: client1.id },
        ]
    });


    // --- Client 2: Tech Solutions S.A. (Lucro Presumido, Services) ---
    const client2 = await prisma.client.create({
        data: {
            name: 'Tech Solutions S.A.',
            cnpj: '98.765.432/0001-10',
            regime: 'LUCRO_PRESUMIDO',
            status: 'ATENDIMENTO',
            responsibleId: admin.id,
        },
    });

    // Create Login for Client 2
    await prisma.user.create({
        data: {
            email: 'ceo@techsolutions.com',
            name: 'Roberto Tech',
            password: clientPassword,
            role: 'CLIENT',
            clientId: client2.id,
        },
    });

    // Seed Data for Client 2
    // Taxes (More complex)
    await prisma.tax.createMany({
        data: [
            { name: 'PIS/COFINS', amount: 5400.00, dueDate: new Date('2026-02-25'), status: 'PENDING', clientId: client2.id },
            { name: 'ISSQN', amount: 2100.00, dueDate: new Date('2026-02-10'), status: 'PENDING', clientId: client2.id },
            { name: 'IRPJ/CSLL Trimestral', amount: 12500.00, dueDate: new Date('2026-04-30'), status: 'PENDING', clientId: client2.id },
            { name: 'INSS Patronal', amount: 8900.00, dueDate: new Date('2026-02-20'), status: 'PENDING', clientId: client2.id },
        ],
    });

    // Employees (Tech Staff)
    await prisma.employee.createMany({
        data: [
            { name: 'Roberto Tech', role: 'Diretor', admissionDate: new Date('2019-01-01'), salary: 15000.00, status: 'ACTIVE', clientId: client2.id },
            { name: 'Amanda Dev', role: 'Senior Dev', admissionDate: new Date('2020-03-10'), salary: 12000.00, status: 'ACTIVE', clientId: client2.id },
            { name: 'Lucas QA', role: 'QA Engineer', admissionDate: new Date('2022-07-01'), salary: 7500.00, status: 'ACTIVE', clientId: client2.id },
            { name: 'Fernanda HR', role: 'RH', admissionDate: new Date('2021-05-15'), salary: 5500.00, status: 'ACTIVE', clientId: client2.id },
        ],
    });

    // Legal Processes (More complex)
    await prisma.legalProcess.createMany({
        data: [
            { title: 'Alteração de Endereço', description: 'Mudança para nova sede em SP.', status: 'DONE', stage: 'Finalizado', clientId: client2.id },
            { title: 'Registro de Marca', description: 'Registro junto ao INPI.', status: 'IN_PROGRESS', stage: 'Aguardando Oposição', clientId: client2.id },
        ],
    });

    // Protocols
    await prisma.protocol.createMany({
        data: [
            { code: 'PRT-2026-002', type: 'Contrato', description: 'Alteração Contratual Registrada', receivedAt: new Date('2026-02-01T10:00:00Z'), clientId: client2.id },
            { code: 'PRT-2026-004', type: 'Certidão', description: 'CND Federal', receivedAt: new Date('2026-02-06T09:30:00Z'), clientId: client2.id },
        ],
    });

    // Financial Records
    await prisma.financialRecord.createMany({
        data: [
            { description: 'Honorários Contábeis - Fev/2026', amount: 2850.00, type: 'INCOME', dueDate: new Date('2026-02-10'), status: 'LATE', clientId: client2.id },
        ],
    });

    // Tickets
    await prisma.ticket.create({
        data: {
            title: 'Holerite Errado',
            description: 'O valor do VA veio descontado a mais.',
            status: 'IN_PROGRESS',
            priority: 'HIGH',
            clientId: client2.id,
        },
    });

    console.log('✅ Seed completed with comprehensive data!');
}

main()
    .catch((e) => {
        console.error(e);
        process.exit(1);
    })
    .finally(async () => {
        await prisma.$disconnect();
    });
