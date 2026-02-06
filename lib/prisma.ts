import { PrismaClient } from '@prisma/client';

const prisma = (global as any).prisma || new PrismaClient({
    datasources: {
        db: {
            url: process.env.DATABASE_URL || "mysql://jca:jca@localhost:3306/jca"
        }
    }
});

if (process.env.NODE_ENV !== "production") (global as any).prisma = prisma;

export default prisma;
