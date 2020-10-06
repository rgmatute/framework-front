import Vue from 'vue'

export class SeguridadService {

    
    public logout(url,_success,_catch,_error):any {
        Vue.prototype.$axios.get('/security/accounts/logout')
        .then((response) => {
          return _success(response);
        }).catch((e) => {
          return _catch(e);
        }).then(() => {
          return _error();
        })
    }


}
