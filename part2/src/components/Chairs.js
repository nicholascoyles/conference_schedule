import React from 'react';

/**
* Chairs component displays the name of the Chair of a session
*
* @author Nicholas Coyles
*/
class Chairs extends React.Component {

    state = {
        display:false,
        displayFurther:false
      }
     
      handleTitleClick = () => {
        this.setState({display:!this.state.display})
      }
    
      render() {
          return (
            <p>Sesssion Chair: {this.props.details.Session_chair}</p>
        );
      }
 }
 
export default Chairs;