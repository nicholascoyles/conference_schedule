import React from 'react';

/**
* SelectDay component allows for a specific day to be selected and 
* the data will be filtered by the day selected 
*
* @author Nicholas Coyles
*/
class SelectDay extends React.Component {
    render() {
      return (
        <label>
          Conference Day:
          <select value={this.props.Conference_day} onChange={this.props.handleSelect}>
            <option value="">Any</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
          </select>
        </label>
      )
    }
   }
   export default SelectDay;