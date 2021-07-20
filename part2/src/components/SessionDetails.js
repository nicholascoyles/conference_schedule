import React from 'react';
import PresentationDetails from './PresentationDetails.js';

/**
* SessionDetails component displays a sessions title and the relvent info for that session
*
* @author Nicholas Coyles
*/
class SessionDetails extends React.Component {

  state = {display:false, data:[]}

  loadContentDetails = () => {
    const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/awards?contentId=" + this.props.details.contentId
    fetch(url)
      .then( (response) => response.json() )
      .then( (data) => {
        this.setState({data:data.data})
      })
       .catch ((err) => {
         console.log("something went wrong ", err)
      }
    );
  }
 
 
  handleSessionClick = (e) => {
    this.setState({display:!this.state.display})
    this.loadContentDetails()
  }
 
  handleDetailClick = (e) => {
    this.setState({displayFurther:!this.state.displayFurther})
    this.loadContentDetails()
  }

  render() {
 
    let content = ""
    let info = "";

    if (this.state.displayFurther) {
      content = this.state.data.map( (details, i) => (<PresentationDetails key={i} details={details} />) )
    }


   if (this.state.display) {
    info = <div >
             <p>Session Type: {this.props.details.Presentation_Type}</p>
             <p>Session Chair: {this.props.details.Session_Chair}</p>
             <p>Room: {this.props.details.Room}</p>
             <button onClick={this.handleDetailClick} className="Btn">Presentation Title</button>

          {content}
           </div>
  }

    return (
      <div>
        <h2>{this.props.details.Session_Title}</h2>
        <button onClick={this.handleSessionClick} className="Btn">Session Details</button>
        {info}
      </div>
    );
  }


 }

export default SessionDetails;