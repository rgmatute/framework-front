import * as React from 'react';
import { View, Text, TouchableOpacity, StyleSheet, Image} from 'react-native';
import ButtonNavigation from './ButtonNavigation';

export default function HomeScreen(props) {
    return (
      <View style={ styles.container }>
        <Text style={{ fontSize:20 }}>Home Screen</Text>
        <ButtonNavigation nombre='Ir a Perfil' destino='Perfil' />
        <View>
          
        </View>
      </View>
    );
  }

const styles = StyleSheet.create({
  container: {
    flex: 1, 
    alignItems: 'center', 
    justifyContent: 'center'
  }
});