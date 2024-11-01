import kivy
from kivy.app import App
from kivy.uix.label import button 
from kivy.uix.label import label
from kivy.uix.boxlayout import Boxlayout

class myFirstApp(App):
    def build(self):
        self.lbl=label(text='Hello World')
        self.btn=button(text='Click me'on press self.change_text)
        self.layout=Boxlayout
        self.layout.add_widget(self.lbl)
        self.layout.add_widget(self.btn)
        return self.layout
    
    def change_text(self,instance)
        self.btn.text='welcome'
    myFirstApp().run()
