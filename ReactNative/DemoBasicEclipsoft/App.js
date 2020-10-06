import 'react-native-gesture-handler';
// import AsyncStorage from '@react-native-community/async-storage';
import React from 'react';
// import {View} from 'react-native';
import MyDrawer from './src/views/Drawers';
import LogiComponent from './src/components/LoginComponent';

export default class App extends React.Component {
  
  constructor(){
    super();
    this.state = { user: null };
  }

  componentDidMount(){ // cuando el componente ya esta montado
    console.log('componente ya esta montado');
}

componentWillUnmount(){ // cuando el componente se va a desmontar
    console.log('componente se va a desmontar');
}

componentWillMount(){ // cuando el componente se va a montar
    console.log('componente se va a montar');
}

  render(){
    // return (<MyDrawer />);
    return this.state.user == null ? ( <LogiComponent /> ): ( 
      <MyDrawer />
    );
  }
}