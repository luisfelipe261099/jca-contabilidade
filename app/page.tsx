import React from 'react';
import Header from '../components/Header';
import Hero from '../components/Hero';
import About from '../components/About';
import Services from '../components/Services';
import Contact from '../components/Contact';
import Footer from '../components/Footer';

export default function Home() {
    return (
        <main className="min-h-screen bg-[#020617] text-slate-200 font-sans selection:bg-blue-500/30">
            <Header />
            <Hero />
            <About />
            <Services />
            <Contact />
            <Footer />
        </main>
    );
}
