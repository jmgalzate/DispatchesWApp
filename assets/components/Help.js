import React, {Component} from 'react';
import { createRoot } from 'react-dom/client';


function Help() {
    return (
        <div>
            <h1>Help</h1>
        </div>
    )
}

const helpElement = document.getElementById("help");
const root = createRoot(helpElement);
root.render(<Help />)