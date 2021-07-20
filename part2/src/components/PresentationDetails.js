import React from 'react';

/**
* PresentationDetails component displays all the authors abstact and award of a specific
* presentaion that has been selected
*
* @author Nicholas Coyles
*/
class PresentationDetails extends React.Component {

    state = {
        display:false,
        displayFurther:false
      }
     
      handleTitleClick = () => {
        this.setState({display:!this.state.display})
      }
     
      render() {
     
        let info = "";

        
        if (this.state.display) {
          info = <div>
                   <p >Authors: {this.props.details.Author}</p>
                   <p >Abstract: {this.props.details.Abstract}</p>
                   <p >Award: {this.props.details.Award}</p>
                 </div>
        }

        return (
          <div>
            <h2>{this.props.details.Title}</h2>
            <button onClick={this.handleTitleClick} className="Btn">Presentation Details</button>

            {info}
          </div>
        );
      }
 }

export default PresentationDetails;