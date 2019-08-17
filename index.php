<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHP| MySQL | React.js | Axios Example</title>
    <script src="js/react.production.min.js"></script>
    <script src="js/react-dom.production.min.js"></script>
    <!-- Load Babel Compiler -->
    <script src="js/babel.min.js"></script>
    <script src="js/axios.min.js"></script>
</head>
<body>
<h4>This is head section of this page</h4>
<div id="root"></div>
<script type="text/babel">
    class App extends React.Component{

        handleClick = () =>{
            const url = 'https://jsonplaceholder.typicode.com/posts'
            axios.get(url).then(response => response.data)
                .then((data) => {
                    this.setState({ contacts: data })
                    console.log(this.state.contacts)
                })
        }

        handleClose = () =>{
            this.setState({ contacts: [] })
            console.log(this.state.contacts)
        }




        state = {
            contacts  : []
        }
        render() {
            return (
                <React.Fragment>
                    <h1>Contact Management</h1>
                    <button type="button" onClick={this.handleClick}>Load Data</button>
                    <button type="button" onClick={this.handleClose}>Close Data</button>
                    <table border='1' width='100%' >
                        <tr>
                            <th>Serial</th>
                            <th>Name</th>
                        </tr>

                        {this.state.contacts.map((contact, index) => (
                            <tr><td> { index +1 }</td>
                                <td>{ contact.title }</td>

                            </tr>
                        ))}
                    </table>
                </React.Fragment>
            );
        }
    }

    ReactDOM.render(<App/>,document.getElementById('root'))
</script>

</body>
</html>
