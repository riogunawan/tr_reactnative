import React, {Component} from 'react';
import {
    StyleSheet, Text, View,
    TextInput,
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
    
    render() {
        return (
        <View style={styles.container}>
            <TextInput
                placeholder = "Enter Name"
                onChangeText = { TextInputValue => this.setState({TextInputName: TextInputValue}) }
                underlineColorAndroid = "transparent"
                style = {styles.TextInputStyle}
            ></TextInput>
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
});
