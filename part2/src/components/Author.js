import React from 'react';
import Schedule from './Schedule.js';

/**
* Author component displays the information related to an individual author
*
* @author Nicholas Coyles
*/
class Author extends React.Component {

 state = {display:false, data:[]}

 loadSessionDetails = () => {
   const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/sessions?authorId=" + this.props.details.authorId
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

 handleAuthorClick = (e) => {
   this.setState({display:!this.state.display})
   this.loadSessionDetails()
 }

 render() {

   let sessions = ""
   if (this.state.display) {
     sessions = this.state.data.map( (details, i) => (<Schedule key={i} details={details} />) )
   }

   return (
    <div className="container">
      <div className="container-content">
        <h2 onClick={this.handleAuthorClick}>{this.props.details.author_name}</h2>
        {sessions}
      </div>
    </div>
   );
 }
}

export default Author;