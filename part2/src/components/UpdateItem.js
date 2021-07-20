import React from 'react';
import Chairs from './Chairs.js';

/**
* UpdateItem component displays all the session titles and the relevant
* details for that session. Admins can update the title, non admins
* can see the details but they can't update
*
* @author Nicholas Coyles
*/
class UpdateItem extends React.Component {

state = {Session_Title: this.props.details.Session_Title, display:false, data:[]}

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

handleDescriptionChange = (e) => {
 this.setState({Session_Title:e.target.value})
}

handleDescriptionUpdate = () => {
  this.props.handleUpdateClick(this.props.details.sessionId, this.state.Session_Title)
}
handleButtonClick = () => {
  this.setState({display:!this.state.display})
  this.loadChairDetails()

}


render() {

  let info = "";
  let chairs = ""
  let updateInfo = "";

   chairs = this.state.data.map( (details, i) => (<Chairs key={i} details={details} />) )
 
 
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

  if(this.props.admin_status){
  updateInfo = <div>
      <div className="text-box-container">
      <div className="row">
      <div className="col-75">
      <textarea id="text"
      rows="4" cols="50"
      value={this.state.Session_Title}
      onChange={this.handleDescriptionChange}
      />
      </div>
      </div>
      </div>
      <div>
        <button className="Btn" onClick={this.handleDescriptionUpdate}>Update</button>
      </div>
      </div>
  }

  return (
    <div>
      <h2>Session name: {this.props.details.Session_Title}</h2>
      <div>
      <button className="Btn" onClick={this.handleButtonClick}>Details</button>
      {info}
      </div>
      {updateInfo}
    </div>
  );
}
}

export default UpdateItem;