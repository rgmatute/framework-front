import React from 'react';
import logo from './logo.svg';
import './App.css';

import Header from './components/Header';
//import Navbar from './components/Template/Navbar';
//import Sidebar from './components/Template/Sidebar';
import Footer from './components/Template/Footer';

function App() {
    var a = [{ id: 1, name: 1902 },{ id: 2, name: 1903 }];
    return (
        <>
            <div className="App">
                <Header logo={logo}/>
                <Footer data={a}/>
            </div>
        </>
    );
}

export default App;
