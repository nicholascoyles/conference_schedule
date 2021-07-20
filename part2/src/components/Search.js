import React from 'react';

/**
* Search component alloew a text input to be searched 
*
* @author Nicholas Coyles
*/
class Search extends React.Component {
    render() {
      return (
        <div>
          <p>Search: {this.props.query}</p>
          <input
            className ="textField"
            type='text' 
            placeholder='search'
            value={this.props.query}
            onChange={this.props.handleSearch}
          />
        </div>
      )
    }       
   }
   export default Search;