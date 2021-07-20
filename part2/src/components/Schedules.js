import React from 'react';
import Search from './Search.js';
import SelectDay from './SelectDay';
import Slots from './Slots.js';

/**
* Schedules component displays the main days on the schedule, with the ability to filter the days
* by selecting or searhing
*
* @author Nicholas Coyles
*/
class Schedules extends React.Component {

    constructor(props) {
      super(props);
      this.state = {
        page:1,
        pageSize:9,
        query:"",
        Conference_Day:"",
        data:[]
      }
      this.handleSearch = this.handleSearch.bind(this);
      this.handleSelect = this.handleSelect.bind(this);
    }
    
    componentDidMount() {
      const url = "http://unn-w18011589.newnumyspace.co.uk/KF6012/part1/api/slots?conference_days"
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
    
    handleMoreClick = () => {
      this.setState({page:this.state.page+1})
    }
    
    handlePreviousClick = () => {
      this.setState({page:this.state.page-1})
    }
    
    handleNextClick = () => {
      this.setState({page:this.state.page+1})
    }
    
    handleSearch = (e) => {
      this.setState({page:1,query:e.target.value})
    }
    
    searchString = (s) => {
      return s.toLowerCase().includes(this.state.query.toLowerCase())
    }
    
    searchDetails = (details) => {
      return ((this.searchString(details.Conference_Day)))
    }
    
    handleSelect = (e) => {
      this.setState({page:1,Conference_Day:e.target.value})
    }
    
    selectDetails = (details) => {
      return ((this.state.Conference_Day === details.Conference_Day) || (this.state.Conference_Day === ""))
    }
    
    render() {
    
      let filteredData =  (
        this.state.data       
        .filter(this.selectDetails)
        .filter(this.searchDetails)
      )
    
      let noOfPages = Math.ceil(filteredData.length/this.state.pageSize)
      if (noOfPages === 0) {noOfPages=1}
      let disabledPrevious = (this.state.page <= 1)
      let disabledNext = (this.state.page >= noOfPages)
    
      return (
        <div>
          <h1>Schedule</h1>

          <SelectDay className="dropdown" Conference_Day={this.state.Conference_Day} handleSelect={this.handleSelect} />

          

          <Search query={this.state.query} handleSearch={this.handleSearch}/>
          { 
            filteredData
            .slice(((this.state.pageSize*this.state.page)-this.state.pageSize),(this.state.pageSize*this.state.page))
            .map( (details, i) => (<Slots key={i} details={details} />) )
          }


          
          <button className="Btn" onClick={this.handlePreviousClick} disabled={disabledPrevious}>Previous</button>
          Page {this.state.page} of {noOfPages}
          <button className="Btn" onClick={this.handleNextClick} disabled={disabledNext}>Next</button>
        </div>
      );
    }
    }

    export default Schedules;