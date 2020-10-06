import React from 'react';
import { View, Text } from 'react-native';
import ButtonNavigation from './ButtonNavigation';

export default class Profile extends React.Component {
    
    render(){
        return (
            <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>
                <Text style={{ fontSize:20 }}>Perfil Screen</Text>
                <ButtonNavigation nombre='Ir a Notificacion' destino='Notificacion' />
            </View>
        );
    }
}