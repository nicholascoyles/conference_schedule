import React from 'react';
import { BrowserRouter as Router, Switch, Route, NavLink } from "react-router-dom";
import Schedules from './components/Schedules.js';
import Authors from './components/Authors.js';
import Admin from './components/Admin.js';
import NotFound404 from './components/NotFound404.js';
import './App.css';

/**
* The main page App brings together all the components and manages
* the routes between pages and creates the nav bar
*
* @author Nicholas Coyles
*/
function App() {
return (
<Router basename="/KF6012/part2">
  <div className="App">
    <div>
     <nav>
       <input type="checkbox" id="check"/>
       <label className="checkbtn" htmlFor="check">&#9776;</label>
         <ul>
           <li>
             <NavLink activeClassName="selected" exact to="/schedules">Schedules</NavLink>
           </li>
           <li>
            <NavLink activeClassName="selected" to="/authors">Authors</NavLink>
          </li>
            <li>
            <NavLink activeClassName="selected" to="/admin">Admin</NavLink>
        </li>
      </ul>
    </nav>
    <Switch>
      <Route exact path="/">
        <Schedules />
      </Route>
      <Route path="/schedules">
        <Schedules />
      </Route>
      <Route path="/authors">
        <Authors />
      </Route>
      <Route path="/admin">
        <Admin />
      </Route>
      <Route path="*">
        <NotFound404 />
      </Route>
    </Switch>
   </div>
  </div>
</Router>
);
}

export default App;