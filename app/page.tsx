import React from 'react';
import Header from '../components/Header';
import Hero from '../components/Hero';
import About from '../components/About';
import Services from '../components/Services';
import Contact from '../components/Contact';
import Footer from '../components/Footer';

export default function Home() {
    return (
        <main className="min-h-screen text-[#163244] selection:bg-cyan-200/70">
            <Header />
            <Hero />
            <About />
            <Services />
            <Contact />
            <Footer />
        </main>
    );
}
