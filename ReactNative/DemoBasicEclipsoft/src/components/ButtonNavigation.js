import * as React from 'react';
import { Text, TouchableOpacity, StyleSheet} from 'react-native';
import * as RootNavigation  from '../routes/RootNavigation';

export default function ButtonNavigation({nombre, destino}){
    return (
        <TouchableOpacity style={styles.touchableOpacity }
          onPress={()=> RootNavigation.navigate(destino) }>
            <Text style={ styles.touchableOpacityText }>{nombre}</Text>
        </TouchableOpacity>
    );
}

const styles = StyleSheet.create({
    touchableOpacity: {
      marginTop:20, 
      width:200, 
      height:50, 
      backgroundColor:'#ff5204',
      padding:10,
      alignItems:'center',
      borderRadius:5
    },
    touchableOpacityText:{
      color:'#fff',
      fontSize:20
    }
});