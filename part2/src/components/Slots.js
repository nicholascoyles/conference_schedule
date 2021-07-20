import React from 'react';
import SlotDetails from './SlotDetails.js';

/**
* Slots components displays the main conference days and all the time slots for that day
*
* @author Nicholas Coyles
*/
class Slots extends React.Component {

 state = {display:false, data:[]}

 loadDays = () => {
   const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/slots?day_slots=" + this.props.details.Conference_Day
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

 handleDayClick = (e) => {
   this.setState({display:!this.state.display})
   this.loadDays()
 }

 render() {

   let dayInfo = ""
   if (this.state.display) {
    dayInfo = this.state.data.map( (details, i) => (<SlotDetails key={i} details={details} />) )
   }

   return (
     <div className="container">
       <div className="container-content">
       <h2 >{this.props.details.Conference_Day}</h2>
       <button onClick={this.handleDayClick} className="Btn">Slots</button>
       {dayInfo}
       </div>
     </div>
   );
 }
}

export default Slots;