import React,{ useEffect, useState, useRef } from 'react';

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
    return coordenadas.latitud == null ? (<div>Cargando...</div>) :
    (
        <>
            <h2>Latitud: {coordenadas.latitud}</h2>
            <h2>Longitud: {coordenadas.longitud}</h2>
            <h2>Message: {props.lat}</h2>
        </>
    );
}
