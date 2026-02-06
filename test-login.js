const { PrismaClient } = require('@prisma/client');
const bcrypt = require('bcryptjs');

const prisma = new PrismaClient();

async function testLogin() {
    try {
        console.log('🔍 Testing admin login...\n');

        // Find admin user
        const user = await prisma.user.findUnique({
            where: { email: 'admin@example.com' }
        });

        if (!user) {
            console.log('❌ User not found!');
            return;
        }

        console.log('✅ User found:');
        console.log('   Email:', user.email);
        console.log('   Name:', user.name);
        console.log('   Role:', user.role);
        console.log('   Password Hash:', user.password.substring(0, 20) + '...');

        // Test password
        const testPassword = 'admin';
        const passwordMatch = await bcrypt.compare(testPassword, user.password);

        console.log('\n🔐 Password Test:');
        console.log('   Testing password:', testPassword);
        console.log('   Result:', passwordMatch ? '✅ MATCH' : '❌ NO MATCH');

        if (!passwordMatch) {
            console.log('\n⚠️  Password does not match! Updating...');
            const newHash = await bcrypt.hash('admin', 10);
            await prisma.user.update({
                where: { email: 'admin@example.com' },
                data: { password: newHash }
            });
            console.log('✅ Password updated successfully!');
        }

    } catch (error) {
        console.error('❌ Error:', error);
    } finally {
        await prisma.$disconnect();
    }
}

testLogin();
