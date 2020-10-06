

export default class MediaHandler {
    getPermisions(){
        return new Promise((res, rej)=>{
            navigator.mediaDevices.getUserMedia({video: true, audio: true})
            .then((stream) => {
                res(stream);
            })
            .catch(err => {
                throw new Error(`Unable to fetch stream ${err}`);
            })
        })
    }

    // stop both mic and camera
    stopBothVideoAndAudio(stream) {
        stream.getTracks().forEach(function(track) {
            if (track.readyState === 'live') {
                track.stop();
            }
        });
    }

    // stop only camera
    stopVideoOnly(stream) {
        stream.getTracks().forEach(function(track) {
            if (track.readyState === 'live' && track.kind === 'video') {
                //track.pause();
                console.log(track);
                track.stop();
            }
        });
    }

    // play only camera
    playVideoOnly(stream) {
        stream.getTracks().forEach(function(track) {
            if (track.readyState === 'live' && track.kind === 'video') {
                track.play();
            }
        });
    }

    // stop only mic
    stopAudioOnly(stream) {
        stream.getTracks().forEach(function(track) {
            if (track.readyState === 'live' && track.kind === 'audio') {
                console.log(track);
                track.stop();
                console.log(track);
            }
        });
    }
}