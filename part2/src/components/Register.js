import React from 'react';

/**
* Register component creates the register form and manages the inputs
* when register is click the contents is sent to handleRegisterClick 
*
* @author Nicholas Coyles
*/
class Register extends React.Component {

render() {
  return (

    <div className="formwrapper">

    <h2>Register</h2>
    <div className="formContainer">

      <div className="row">
      <label htmlFor="email">Email</label>
       <input
         type='text' 
         className="emailInput"
         placeholder='email'
         value={this.props.registerEmail}
         onChange={this.props.handleRegisterEmail}
       />
       </div>

       <div className="row">
      <label htmlFor="username">Username</label>
       <input
         type='text' 
         className="usernameInput"
         placeholder='username'
         value={this.props.registerusermame}
         onChange={this.props.handleRegisterUsername}
       />
       </div>

       <div className="row">
       <label htmlFor="password">Password</label>
       <input
         type='password' 
         className="passwordInput"
         placeholder='password'
         value={this.props.registerpassword}
         onChange={this.props.handleRegisterPassword}
       />
       </div>

      <button className="Btn" onClick={this.props.handleRegisterClick}>Register</button>

    </div>

    </div>
  );
}
}

export default Register;