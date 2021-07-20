import React from 'react';
import SessionDetails from './SessionDetails.js';

/**
* SlotDetails component dispays a time slots and the session titles in that times slot
*
* @author Nicholas Coyles
*/

class SlotDetails extends React.Component {

  state = {display:false, data:[]}

  loadSlotDetails = () => {
    const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/sessions?slotId=" + this.props.details.slotId
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
 
  handleSlotClick = (e) => {
    this.setState({display:!this.state.display})
    this.loadSlotDetails()
  }
 
  render() {
 
    let sessionTitle = ""
   if (this.state.display) {
     sessionTitle = this.state.data.map( (details, i) => (<SessionDetails key={i} details={details} />) )
   }
 
    return (
      <div>
        <h2 onClick={this.handleSlotClick}>{this.props.details.Time_Slot}</h2>
        {sessionTitle}
      </div>
    );
  }
 }

export default SlotDetails;