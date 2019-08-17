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

    class ContactForm extends React.Component {

        state = {
            name: '',
            email: '',
            job: ''
        }

        handleFormSubmit(event) {
            event.preventDefault();
            this.setState({name: '', email: '', job: ''});
            let formData = new FormData();

            formData.append('name', this.state.name)
            formData.append('email', this.state.email)
            formData.append('job', this.state.job)

            axios({
                method: 'POST',
                url: 'process.php',
                data: formData,
            }).then(function (response) {
                console.log(response)
            }).catch(function (response) {
                console.log(response)
            })


        }

        render() {

            return (
                <div>
                    <form>
                        <label>Name</label>
                        <input type="text" name="name" value={this.state.name}
                               onChange={e => this.setState({name: e.target.value})} autoFocus/>

                        <label>Email</label>
                        <input type="email" name="email" value={this.state.email}
                               onChange={e => this.setState({email: e.target.value})}/>

                        <label>Job</label>
                        <input type="text" name="job" value={this.state.job}
                               onChange={e => this.setState({job: e.target.value})}/>

                        <input type="submit" onClick={e => this.handleFormSubmit(e)} value="Create Contact"/>
                    </form>
                </div>
            );
        }
    }

    class EditContactForm extends React.Component {

        state = {
            name: 'defualt name',
            email: 'default@gmail.com',
            job: 'software engineer'
        }


        constructor(props) {
            super(props);
            console.log(props)
        }

        componentDidMount() {
            const url = 'process.php?status=edit&id=' + this.props.editId;
            axios.get(url).then(response => response.data)
                .then((data) => {

                    this.setState(
                        {
                            name: data.name,
                            email: data.email,
                            job: data.job
                        })
                })
        }

        handleFormSubmit(event) {
            event.preventDefault();
            this.setState({name: '', email: '', job: ''});
            let formData = new FormData();

            formData.append('name', this.state.name)
            formData.append('email', this.state.email)
            formData.append('job', this.state.job)

            axios({
                method: 'POST',
                url: 'process.php',
                data: formData,
            }).then(function (response) {
                console.log(response)
            }).catch(function (response) {
                console.log(response)
            })


        }

        render() {


            return (
                <div>
                    <form>
                        <label>Name</label>
                        <input type="text" name="name" value={this.state.name}
                               onChange={e => this.setState({name: e.target.value})} autoFocus/>

                        <label>Email</label>
                        <input type="email" name="email" value={this.state.email}
                               onChange={e => this.setState({email: e.target.value})}/>

                        <label>Job</label>
                        <input type="text" name="job" value={this.state.job}
                               onChange={e => this.setState({job: e.target.value})}/>

                        <input type="submit" onClick={e => this.handleFormSubmit(e)} value="Create Contact"/>
                    </form>
                </div>
            );
        }
    }

    class App extends React.Component {

        componentDidMount() {

            const url = 'process.php?status=all'
            axios.get(url).then(response => response.data)
                .then((data) => {

                    this.setState({contacts: data})
                })
        }


        handleDelete(index) {
            const url = 'process.php?status=delete&id=' + index;
            axios.get(url).then(response => response.data)

        }

        handleEdit(index) {
            this.setState({
                id: index
            })
        }

        componentDidUpdate() {
            const url = 'process.php?status=all'
            axios.get(url).then(response => response.data)
                .then((data) => {
                    this.setState({contacts: data})
                })
        }

        state = {
            contacts: [],
            id: null,
        }

        render() {
            let form;
            if (this.state.id == null) {
                form = <ContactForm/>
            } else {
                form = <EditContactForm editId={this.state.id}/>
            }

            return (
                <React.Fragment>

                    {form}
                    <br/><br/>
                    <table border='1' width='50%'>
                        <tr>
                            <th>Serial</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Job</th>
                            <th>Action</th>
                        </tr>

                        {this.state.contacts.map((contact, index) => (
                            <tr>
                                <td> {index + 1}</td>
                                <td>{contact.name}</td>
                                <td>{contact.email}</td>
                                <td>{contact.job}</td>
                                <td>
                                    <td>
                                        <button type="button" onClick={() => this.handleEdit(contact.id)}>Edit
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" onClick={() => this.handleDelete(contact.id)}>Delete
                                        </button>
                                    </td>
                                </td>

                            </tr>
                        ))}
                    </table>


                </React.Fragment>
            );
        }
    }

    ReactDOM.render(<App/>, document.getElementById('root'))
</script>

</body>
</html>
