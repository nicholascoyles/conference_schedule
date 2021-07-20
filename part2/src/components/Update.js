import React from 'react';
import UpdateItem from './UpdateItem.js';

/**
* Update component displays the contents of the updateItem component
*
* @author Nicholas Coyles
*/
class Update extends React.Component {

 state = {data:[]}

componentDidMount() {
 const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/sessions"
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


render() {

    return (
      <div>
        {this.state.data.map((details,i) => (<UpdateItem key={i} details={details} admin_status={this.props.admin_status} handleUpdateClick={this.props.handleUpdateClick}/>))}
      </div>
    );
  }

}

export default Update;

