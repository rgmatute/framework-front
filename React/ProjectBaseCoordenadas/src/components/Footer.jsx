import React from 'react';
import Coordenadas from './Coordenadas';

export default class Footer extends React.Component {
    state = { 
        message: 'ds'
    };

    componentDidMount(){
        console.log('componentDidMount',this.props);
    }
    componentWillUnmount(){
        console.log('componentWillUnmount',this.props);
    }
    pruebaEvent(message){
        this.setState({ message: message.target.value});
    }
    render(){
        return (
            <>
                <footer>
                    {
                        this.props.data.map(el => <span key={el.id}>Footer component mounted { el.name }</span>)
                    }
                    <input type="text" onChange={ this.pruebaEvent.bind(this) }/>
                    <span>{ this.state.message }</span>
                    <Coordenadas lat={this.state.message}></Coordenadas>
                </footer>
            </>
        );
    }
}