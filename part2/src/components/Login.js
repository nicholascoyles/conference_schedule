import React from 'react';

/**
* Login component creates the login form and passed the inputs 
* to handLonginClick when login is pressed
*
* @author Nicholas Coyles
*/
class Login extends React.Component {

render() {
  return (

    <div className="formwrapper">

      <h2>Login</h2>
    <div className="formContainer">

      <div className="row">
      <label htmlFor="email">Email</label>
       <input
         type='text' 
         className="emailInput"
         placeholder='email'
         value={this.props.email}
         onChange={this.props.handleEmail}
       />
       </div>

       <div className="row">
       <label htmlFor="password">Password</label>
       <input
         type='password' 
         className="passwordInput"
         placeholder='password'
         value={this.props.password}
         onChange={this.props.handlePassword}
       />
       </div>

      <button className="Btn" onClick={this.props.handleLoginClick}>Log in</button>

    </div>
    
    </div>
  );
}
}

export default Login;