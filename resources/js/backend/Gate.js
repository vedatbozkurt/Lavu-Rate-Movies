export default class Gate{

    constructor(user){
        this.user = user;
    }


    isAdmin(){
        return this.user.role === 1;
    }

    isUser(){
        return this.user.role === 0;
    }

    isEditor(){
        return this.user.role === 2;
    }
    isAdminOrEditor(){
        if(this.user.role === 1 || this.user.role === 2){
            return true;
        }

    }
    isUserOrEditor(){
        if(this.user.role === 0 || this.user.role === 2){
            return true;
        }
    }



}

