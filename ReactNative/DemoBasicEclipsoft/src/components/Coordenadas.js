import React,{ useEffect, useState, useRef } from 'react';
import { View, Text} from 'react-native';

function useCoordenadas(){
    const [coordenadas, setCoordenadas] = useState({
        latitud: null,
        longitud: null
    });
    const geoId = useRef(null);
    useEffect(() => {
        geoId.current = window.navigator.geolocation.watchPosition(position=>{ 
            setCoordenadas({ 
                latitud: position.coords.latitude,
                longitud: position.coords.longitude
            });
        });
        return () => {
            navigator.geolocation.clearWatch(geoId.current)
        }
    });
    return coordenadas;
}

export default function Coordenadas(props){
    //console.log(props);
    const coordenadas = useCoordenadas();
    //console.log(this);
    return coordenadas.latitud == null ? (
        <View>
            <Text>Cargando...</Text>
        </View>
    ) :
    (
        <>
            <View>
                <Text>Latitud: {coordenadas.latitud}</Text>
                <Text>Longitud: {coordenadas.longitud}</Text>
                <Text>Message: {props.message}</Text>
            </View>
        </>
    );
}
