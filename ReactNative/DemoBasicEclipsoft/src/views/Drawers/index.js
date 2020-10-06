import * as React from 'react';
import { View, Text, TouchableOpacity, Image} from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import { createDrawerNavigator, DrawerItemList } from '@react-navigation/drawer';
import s from './style';
import  Icon from 'react-native-vector-icons/FontAwesome';
import Profile from '../../components/ProfileComponent';
import { navigationRef } from '../../routes/RootNavigation';
import NotificacionScreen from '../../components/NotificacionScreen';
import HomeScreen from '../../components/HomeScreen';

function DrawerMenu(props){
    return (
        <TouchableOpacity onPress={props.navigation}>
            <View style={s.menuContainer}>
                <View style={s.iconoContainer}>
                    <Icon size={17} name={props.iconName}/>
                </View>
                <View style={s.tituloContainer}>
                    <Text style={s.tituloTxt}>{props.titleName}</Text>
                </View>
            </View>
        </TouchableOpacity>
    );
}

function Menu(props){
    return (
        <>
            <View style={s.container}>
                <View style={s.bgContainer}>
                    <TouchableOpacity onPress={() => props.navigation.navigate('Perfil')}>
                        <View style={s.userContainer}>
                            <Image style={s.userImagen} source={ require('./logo-brainapps.png')}/>
                            <View style={s.camaraContainer}>
                                <Image style={s.camaraIcon} source={ require('./photo-camera.png') } />
                            </View>
                        </View>
                        <View style={s.userNombre}>
                            <Text style={s.userTitulo}>BrainApps</Text>
                            <Text style={s.userSubTitulo}>Ver Perfil</Text>
                        </View>
                    </TouchableOpacity>
                </View>
                
                <DrawerMenu iconName='home' titleName='Home' navigation={ () => props.navigation.navigate('Home') }/>
                <DrawerMenu iconName='user' titleName='Perfil' navigation={ () => props.navigation.navigate('Perfil') }/>
                <DrawerMenu iconName='bell' titleName='Notificacion' navigation={ () => props.navigation.navigate('Notificacion') }/>
            </View>
        </>
    );
}

const Drawer = createDrawerNavigator();

function MyDrawer() {
  return (
    <NavigationContainer ref={navigationRef}>
        <Drawer.Navigator drawerContent={(props)=> <Menu {...props}/>}>
            <Drawer.Screen name="Home" component={HomeScreen} />
            <Drawer.Screen name="Perfil" component={Profile} />
            <Drawer.Screen name="Notificacion" component={NotificacionScreen} />
        </Drawer.Navigator>
    </NavigationContainer>
  );
}

export default MyDrawer;