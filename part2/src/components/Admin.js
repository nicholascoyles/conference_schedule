import React from 'react';
import Login from './Login.js';
import Register from './Register.js';
import Update from './Update.js';

/**
* Admin component deals with posting inputs into the login and register forms to the 
* relevant enpoints and handes the information sent back.
*
* @author Nicholas Coyles
*/
class Admin extends React.Component {

state = {
  
  "authenticated":false,
   "email":"",
   "password":"",
   "registerEmail":"",
   "registerPassword":"",
   "registerUsername":"",
   "admin_status":""
  }

postData = (url, myJSON, callback) => {
  fetch(url, {   method: 'POST',
                 headers : new Headers(),
                 body:JSON.stringify(myJSON)})
    .then( (response) => response.json() )
    .then( (data) => {
      callback(data)
    })
    .catch ((err) => {
      console.log("something went wrong ", err)
    }
  );
}

handlePassword = (e) => {
  this.setState({password:e.target.value})
}
handleEmail = (e) => {
  this.setState({email:e.target.value})
}

handleRegisterEmail = (e) => {
  this.setState({registerEmail:e.target.value})
}

handleRegisterPassword = (e) => {
  this.setState({registerPassword:e.target.value})
}

handleRegisterUsername = (e) => {
  this.setState({registerUsername:e.target.value})
}

constructor(props) {
  super(props);
  this.state = {"authenticated":false,
                "email":"",
                "password":"",
                "registerEmail":"",
                "registerPassword":"",
                "registerUsername":"",
                "admin_status":""
                }

  this.handleEmail = this.handleEmail.bind(this);
  this.handlePassword = this.handlePassword.bind(this);
  this.handleRegisterEmail = this.handleRegisterEmail.bind(this);
  this.handleRegisterPassword = this.handleRegisterPassword.bind(this);
  this.handleRegisterUsername = this.handleRegisterUsername.bind(this);

}

loginCallback = (data) => {
  console.log(data)
  if (data.status === 200) {
    this.setState({"authenticated":true, "token":data.token})
    localStorage.setItem('myToken', data.token);
    
    if(data.admin === "1"){
      this.setState({"admin_status":true})
      localStorage.setItem('admin_status', "1");

    }else{
      this.setState({"admin_status":false})
      localStorage.setItem('admin_status', "0");

    }
    
  }
}
registerCallback = (data) => {
  console.log(data)
  
}

updateCallback = (data) => {
  console.log(data)
  if (data.status !== 200) {
    this.setState({"authenticated":false})
    localStorage.removeItem('myToken');
  }  
}

componentDidMount() {
  if(localStorage.getItem('myToken')) {
    this.setState({"authenticated":true});
  } 

  if(localStorage.getItem('admin_status') === "1") {
    this.setState({"admin_status":true});
  }else{
    this.setState({"admin_status":false});
  }

}

handleLoginClick = () => {
  const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/login"
  let myJSON = {"email":this.state.email,"password":this.state.password}
  this.postData(url, myJSON, this.loginCallback)
}

handleRegisterClick = () => {
  const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/register"
  let myJSON = {"registerEmail":this.state.registerEmail,"registerPassword":this.state.registerPassword,"registerUsername":this.state.registerUsername}
  this.postData(url, myJSON, this.registerCallback)
}



handleLogoutClick = () => {
  this.setState({"authenticated":false})
  localStorage.removeItem('myToken');  
  this.setState({"admin_status":false})
  localStorage.removeItem('admin_status');  
}





handleUpdateClick = (sessionId, name) => {
  const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/update"

  if (localStorage.getItem('myToken')) {
    let myToken = localStorage.getItem('myToken')
    let myJSON = {
      "token":myToken,
      "sessionId": sessionId,
      "name":name
     }
     this.postData(url, myJSON, this.updateCallback)
   } else {
     this.setState({"authenticated":false})
     this.setState({"admin_status":false})
   }
 } 

 render() {
  let page = <Login handleLoginClick={this.handleLoginClick} email={this.state.email} password={this.props.password} handleEmail={this.handleEmail} handlePassword={this.handlePassword}/>
 
  let page2 = <Register handleRegisterClick={this.handleRegisterClick} registerEmail={this.state.registerEmail} registerUsername={this.state.registerUsername} registerpassword={this.props.registerpassword} handleRegisterEmail={this.handleRegisterEmail} handleRegisterUsername={this.handleRegisterUsername} handleRegisterPassword={this.handleRegisterPassword}/>
 
  if (this.state.authenticated) {
    page = <div>
            <button onClick={this.handleLogoutClick}>Log out</button>
            <Update handleUpdateClick={this.handleUpdateClick} admin_status={this.state.admin_status}/>             
           </div>
    page2="";
          }
        
  return (
    <div>
      <h1>Admin</h1>
      {page}
      {page2}
    </div>
  );
}

}

export default Admin;