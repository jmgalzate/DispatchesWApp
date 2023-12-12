import React, { Component } from 'react';
import { createRoot } from 'react-dom/client';

console.log('Session component loaded');

class Session extends Component {
    
    render() {
        return (
            <div className="session">
                <h2>Session</h2>
                <p>Session component</p>
            </div>
        );
    }
}