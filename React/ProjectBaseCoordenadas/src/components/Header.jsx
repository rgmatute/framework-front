import React from 'react';

import Prueba from './Prueba';

export default class Header extends React.Component {
    render(){
        return (
            <>
                <header className="App-header">
                    <img src={ this.props.logo } className="App-logo" alt="logo" />
                    <p>
                        Edit <code>src/App.js</code> and save to reload. h
                    </p>
                    <a
                        className="App-link"
                        href="https://reactjs.org"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <Prueba>Learn React</Prueba>
                    </a>
                </header>
            </>
        );
    }
}