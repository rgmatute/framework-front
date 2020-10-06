import * as React from 'react';
import { View, Text, TouchableOpacity, Image} from 'react-native';
import ButtonNavigation from './ButtonNavigation';

export default function NotificacionScreen() {
    return (
      <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>
        <Text style={{ fontSize:20 }}>Notificacion Screen</Text>
        <ButtonNavigation nombre='Ir a Home' destino='Home' />
      </View>
    );
}