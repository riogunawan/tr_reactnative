import React, {Component} from 'react';
import {
    StyleSheet, Text, View,
    TextInput, TouchableOpacity, Alert
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
        </View>
        );
    }
}

export default App1 = createStackNavigator({
    First: { screen: InputUsers }
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
});
