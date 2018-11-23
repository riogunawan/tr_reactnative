import React, {Component} from 'react';
import {
    StyleSheet, Text, View,
    TextInput, TouchableOpacity, Alert,
    ListView, ActivityIndicator,
} from 'react-native';

import { createStackNavigator } from 'react-navigation';

class InputUsers extends Component{
    static navigationOptions = {
        title: "Input Users"
    }

    constructor(props) {
      super(props)
    
      this.state = {
         TextInputName : "",
         TextInputEmail : "",
         TextInputPhoneNumber : "",
      }
    }

    InsertUsers = () => {
        const {TextInputName} = this.state;
        const {TextInputEmail} = this.state;
        const {TextInputPhoneNumber} = this.state;

        // Alert.alert('hello');
        fetch('http://riogunawan/crud/insert.html', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: TextInputName,
                email: TextInputEmail,
                phone_number: TextInputPhoneNumber,
            })
        }).then( (response) => response.json() )
          .then( (responseJson) => {
              Alert.alert(responseJson);
          } ).catch( (error) => {
              console.error(error);
          } )
    }

    ViewUsersList = () => {
        this.props.navigation.navigate('Second');
    }
    
    render() {
        return (
        <View style={styles.container}>
            <TextInput
                placeholder = "Enter Name"
                onChangeText = { TextInputValue => this.setState({TextInputName: TextInputValue}) }
                underlineColorAndroid = "transparent"
                style = {styles.TextInputStyle}
            />
            <TextInput
                placeholder = "Enter E-mail"
                onChangeText = { TextInputValue => this.setState({TextInputEmail: TextInputValue}) }
                underlineColorAndroid = "transparent"
                style = {styles.TextInputStyle}
            />
            <TextInput
                placeholder = "Enter Phone Number"
                onChangeText = { TextInputValue => this.setState({TextInputPhoneNumber: TextInputValue}) }
                underlineColorAndroid = "transparent"
                style = {styles.TextInputStyle}
            />
            <TouchableOpacity activeOpacity={.4} style={styles.TouchableOpacityStyle} onPress={ this.InsertUsers } >
                <Text style={ styles.TextStyle } >SIMPAN</Text>
            </TouchableOpacity>
            <TouchableOpacity activeOpacity={.4} style={styles.TouchableOpacityStyle} onPress={ this.ViewUsersList } >
                <Text style={ styles.TextStyle } >List Data</Text>
            </TouchableOpacity>
        </View>
        );
    }
}

class ViewDataUsers extends Component {
    static navigationOptions = {
        title: 'Data Users'
    }

    constructor(props) {
      super(props)
      this.state = {
         isLoading: true
      }
    }

    componentDidMount() {
        return fetch('http://riogunawan/crud/data.html')
                .then( (response) => response.json() )
                .then( (responseJson) => {
                    let ds = new ListView.DataSource({
                        rowHasChanged: (r1, r2) => r1 !== r2
                    })
                    this.setState({
                        isLoading: false,
                        dataSource: ds.cloneWithRows(responseJson)
                    }, function() {})
                } ).catch( (error) => {
                    console.error(error);
                } )
    }
    
    Action_Click(id, name, email, phone_number) {
        // this.props.navigation.navigate('three', {
        //     id: id,
        //     name: name,
        //     email: email,
        //     phone_number: phone_number,
        // })
        Alert.alert(email);
    }

    ListViewItemSeparator = () => {
        return (
            <View
                style = {{
                    height: 0.5,
                    width: '100%',
                    backgroundColor: '#2196F3'
                }}
            />
        )
    }

    render(){
        if (this.state.isLoading) {
            return(
                <View style={{ flex: 1, paddingTop: 20 }}>
                    <ActivityIndicator/>
                </View>
            )
        }
        return(
            <View style={ styles.ContainerDataUsers } >
                <ListView
                    dataSource = { this.state.dataSource }
                    renderSeparator = { this.ListViewItemSeparator }
                    renderRow = { (rowData) =>
                        <Text
                            style={ styles.rowViewContainer }
                            onPress={ this.Action_Click.bind(this,
                                    rowData.id,
                                    rowData.name,
                                    rowData.email,
                                    rowData.phone_number,
                                ) }
                        >
                            { rowData.name }
                        </Text>
                    }
                />
            </View>
        )
    }
}

class UpdateDataUser extends Component {
    render() {
        return(
            <View></View>
        )
    }
}

export default App1 = createStackNavigator({
    First: { screen: InputUsers },
    Second: { screen: ViewDataUsers },
    Three: { screen: UpdateDataUser },
});

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#F5FCFF',
  },
  TextInputStyle: {
      textAlign: 'center',
      marginBottom: 7,
      width: '90%',
      height: 40,
      borderWidth: 1,
      borderRadius: 5,
      borderColor: '#FF5722',
  },
  TextStyle: {
    color: '#FFFFFF',
    textAlign: 'center',
  },
  TouchableOpacityStyle: {
    paddingTop: 10,
    paddingBottom: 10,
    borderRadius: 5,
    marginBottom: 7,
    width: '90%',
    backgroundColor: '#00BCD4',
  },
  ContainerDataUsers: {
    flex: 1,
    paddingTop: 20,
    marginLeft: 5,
    marginRight: 5,
  },
  rowViewContainer: {
    textAlign: 'center',
    fontSize: 20,
    paddingTop: 10,
    paddingRight: 10,
    paddingBottom: 10,
  }
});
