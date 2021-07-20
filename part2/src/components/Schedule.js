import React from 'react';
import Chairs from './Chairs.js';

/**
* Schedule component displays all the relevant data about a specific session
* on the schedule
*
* @author Nicholas Coyles
*/

class Schedule extends React.Component {

  state = {display:false, data:[]}


 loadChairDetails = () => {
  const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/chairs?sessionId=" + this.props.details.sessionId
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


 handleSessionClick = () => {
   this.setState({display:!this.state.display})
   this.loadChairDetails()
 }

 render() {

   let info = "";
   let chairs = ""


   if (this.state.display) {
    chairs = this.state.data.map( (details, i) => (<Chairs key={i} details={details} />) )
  }
  
  if (this.state.display) {
     info = <div className="presentation_details">
              <ul>
                <li>Presentation Type:{this.props.details.Presentation_Type}</li>
                <li>Room:{this.props.details.Room}</li>
                <li>Time Slot:{this.props.details.Time_Slot}</li>
                <li>Conference Day:{this.props.details.Conference_day}</li>
                <li>{chairs}</li>
              </ul>
            </div>
   }

   return (
     <div>
       <h2>{this.props.details.Session_Title}</h2>
       <button className="Btn" onClick={this.handleSessionClick}>Presentation Details</button>

       {info}
     </div>
   );
 }
}

export default Schedule;