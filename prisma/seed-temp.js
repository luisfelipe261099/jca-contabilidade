const { PrismaClient } = require('@prisma/client');
const bcrypt = require('bcryptjs');

const prisma = new PrismaClient();

async function main() {
    const hashedPassword = await bcrypt.hash('admin123', 10);

    const admin = await prisma.user.upsert({
        where: { email: 'admin@jcacontabilidade.com' },
        update: {},
        create: {
            email: 'admin@jcacontabilidade.com',
            name: 'Admin JCA',
            password: hashedPassword,
            role: 'ADMIN',
        },
    });

    console.log('Seed success:', admin.email);
}

main()
    .then(async () => {
        await prisma.$disconnect();
    })
    .catch(async (e) => {
        console.error(e);
        await prisma.$disconnect();
        process.exit(1);
    });
