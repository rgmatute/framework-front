import React from 'react';
import MediaHandler from './MediaHandler';
import Pusher from 'pusher-js';
import Peer from 'simple-peer';

const APP_KEY = "3b05f72b5c23e67904ec";

export default class Video extends React.Component {
    constructor(){
        super();

        this.state = {
            hasMedia: false,
            otherUserId: null,
            videoStop: false,
            audioStop: false,
            userId: Math.floor((Math.random() * 1000) + 1),
            // userId: "userid",
            toUserId: ''
        };

        this.peers = {};
        this.stream = null;


        this.mediaHandler = new MediaHandler();
        this.setupPusher();

        this.callTo = this.callTo.bind(this);
        this.setupPusher = this.setupPusher.bind(this);
        this.startPeer = this.startPeer.bind(this);
    }

    setupPusher(){
        // Pusher.logToConsole = true;
        this.pusher = new Pusher(APP_KEY,{
            authEndpoint: "https://pusher-connection.azurewebsites.net/pusher/auth",
            cluster: "mt1",
            auth: {
                params: this.state.userId,
                headers: {
                    'Authorization': this.state.userId
                }
            }
        });

        this.channel =  this.pusher.subscribe('presence-video-channel');
 
        this.channel.bind(`client-signal-${ this.state.userId }`,(signal)=>{
            let peer = this.peers[signal.userId];

            console.log("signal.userId",signal.userId);
            
            // if peer is not already exists, we got an incoming call
            if(peer === undefined){
                this.setState({ otherUserId: signal.userId });
                peer = this.startPeer(signal.userId, false);
            }else{
                peer.signal(signal.data)
            }
        });
    }

    startPeer(userId, initiator = true){
        const peer = new Peer({
            initiator,
            stream: this.stream,
            trickle: false
        });
        peer._debug = console.log

        peer.on('signal', (data) => {
            this.channel.trigger(`client-signal-${userId}`, {
                type: 'signal',
                userId: this.state.userId,
                data: data
            });
        });

        peer.on('stream', (stream) => {
            try {
                this.userVideo.srcObject = stream;
            } catch (e) {
                this.userVideo.src = URL.createObjectURL(stream);
            }

            this.userVideo.play();
        });

        peer.on('error', function (err) { console.log('error', err) })


        peer.on('close', () => {
            let peer = this.peers[userId];
            if(peer !== undefined) {
                peer.destroy();
            }

            this.peers[userId] = undefined;
        });

        return peer;
    }

    callTo(userId){
        this.peers[userId] = this.startPeer(userId);
    }

    componentWillMount(){
        console.log("componentWillMount --> 2");
        this.mediaHandler.getPermisions()
        .then((stream)=>{
            this.setState({ hasMedia: true });
            this.stream = stream;
            
            try {
                this.myVideo.srcObject = stream;
                //this.myMediaRecorder = new MediaRecorder(stream);
                //this.myMediaRecorder.start();
            } catch (e) {
                this.myVideo.src = URL.createObjectURL(stream);
            }
            this.myVideo.play();
        })
    }

    componentWillUnmount(){
        this.mediaHandler.stopBothVideoAndAudio(this.stream);
        this.mediaHandler = null;
        this.setState({ hasMedia: false, videoStop: true, audioStop: true });
        this.stream = null;
    }
    stopVideo(){
        if(this.state.videoStop){
            this.mediaHandler.stopBothVideoAndAudio(this.stream);
            this.setState({ videoStop: false });
            this.componentWillMount();
            // this.myVideo.play();
        }else{
            //this.myVideo.pause();
            this.mediaHandler.stopVideoOnly(this.stream);
            this.setState({ videoStop: true });
        }
    }

    stopAudio(){
        if(this.state.audioStop){
            this.setState({ audioStop: false });
        }else{
            this.setState({ audioStop: true });
        }
        // this.mediaHandler.stopAudioOnly(this.stream);
        // this.setState({ audioStop: true });
    }

    changeToUserId(event){
        //let test = "userid";
        this.setState({ toUserId: event.target.value});
        //this.setState({ toUserId: test});
    }

    render(){
        console.log("render --> 3");
        return (
            <>
                <span>Mi id: { this.state.userId }</span>
                <div className="video-container">
                    <div>
                        <video muted={true} className="my-video" ref={(ref)=> {this.myVideo = ref;}} title="Ronny Gabriel Matute Granizo"></video>
                        <center><span className="my-video-autor small small">Médico</span></center>
                    </div>
                    <div>
                        <video muted={this.state.audioStop} className="user-video" ref={(ref)=> {this.userVideo = ref;}} title="Ronny Gabriel Matute Granizo"></video>
                        <center><span className="user-video-autor small small">Paciente</span></center>
                    </div>
                </div>
                <div>
                    <button onClick={this.stopVideo.bind(this)}>{ this.state.videoStop ? 'Iniciar video' : 'Detener video'}</button>
                    <button onClick={this.stopAudio.bind(this)}>{ this.state.audioStop ? 'Iniciar audio' : 'Detener audio'}</button>
                </div>
                <div>
                    <input type="text" onChange={ this.changeToUserId.bind(this) }/>
                    <button onClick={ () => this.callTo(this.state.toUserId) }>Llamar!</button>
                    <span>toUserId: { this.state.toUserId }</span>
                </div>
            </>
        )
    }
}