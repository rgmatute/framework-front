import React from 'react';
import { View, Text, StyleSheet, TextInput, TouchableOpacity } from 'react-native';
import  Icon from 'react-native-vector-icons/FontAwesome';
import axios from "axios";


export default class LogiComponent extends React.Component {
    constructor(props){
        super(props);
        this.state = { user: null, email:'admin@nee.com', pass:'admin',token:null, appName: 'Autenticarse' };
        
        // Bind actions
        this.onPress = this.onPress.bind(this);
        this.logOut = this.logOut.bind(this);
    }
    

    onPress(){
        const data = {
            email: this.state.email,
            password: this.state.pass
        };

        axios({
            method: "post",
            url: "https://app-repository.azurewebsites.net/api/v1/security/accounts/login",
            data: data,
            config: { headers: { "Content-Type": "application/json" } }
        })
        .then(response => {
            if(response.data.code !== 0){
                alert(response.data.message.user);
            }else{
                const user = response.data.data.Informacion;
                const token = response.data.data.Token;
                console.log('User: ' ,user);
                console.log('Token: ' , token);
                this.setState({token:token});
                this.setState({appName: user.role_name});
                this.logOut(token);
            }
        })
        .catch(function(error) {
            console.log(error);
        });
    }

    logOut(token){
        axios({
            method: "get",
            url: "https://app-repository.azurewebsites.net/api/v1/security/accounts/logout",
            config: { headers: { "Content-Type": "application/json" } },
            headers: { "Authorization": token }
        }).then(responseLogout => {
            if(responseLogout.data.code !== 0){
                alert(responseLogout.data.message.user);
            }else{
                alert(responseLogout.data.message);
            }
            console.log('logOutResponse: ', responseLogout.data);
        });
    }


    render(){
        return (
            <>
                <View style={styles.container}>
                    <View>
                        <Text style={{color: '#fff', fontSize:20, padding:20 }}>{this.state.appName}</Text>
                    </View>
                    <View style={styles.form}>
                        <View style={{alignItems:'center'}}>
                            <Text style={{fontSize:20,color: 'gray'}}>Correo</Text>
                            <TextInput style={styles.input} value={this.state.email} onChangeText={text => this.setState({email:text}) }/>
                        </View>
                        <View style={{alignItems:'center', paddingTop: 50}}>
                            <Text style={{fontSize:20, color: 'gray'}}>Contraseña</Text>
                            <TextInput style={styles.input} value={this.state.pass} onChangeText={text => this.setState({pass:text}) } secureTextEntry={true}/>
                        </View>
                        <View>
                            <TouchableOpacity style={styles.touchableOpacity } onPress={this.onPress}>
                                <Text style={ styles.touchableOpacityText }>Iniciar Sesion <Icon size={19} name="sign-in"/> </Text>
                            </TouchableOpacity>
                        </View>
                        <View>
                        <TouchableOpacity style={{padding:10}}>
                            <Text style={{color:'gray', fontSize: 10}}>¿No estas registrado? Crear una cuenta</Text>
                        </TouchableOpacity>
                        </View>
                    </View>
                </View>
            </>
        );
    }
}

const styles = StyleSheet.create({
    container: {
        flex: 1, 
        alignItems: 'center', 
        justifyContent: 'center',
        backgroundColor: 'rgb(0, 68, 132)'
    },
    form: {
        backgroundColor: 'white', 
        width: 250, 
        height: 360, 
        alignItems:'center',
        paddingTop:20,
        borderRadius: 10
    },
    touchableOpacity: {
        marginTop:30, 
        width:200, 
        height:50, 
        backgroundColor:'#1976d2',
        padding:10,
        alignItems:'center',
        borderRadius:5
    },
    touchableOpacityText:{
      color:'#fff',
      fontSize: 19
    },
    input: {
        borderColor: 'gray',
        color: 'gray',
        borderBottomWidth: 1, 
        width: 200,
        
    }
});