title: Sök
date: '2019-02-21'
innehall:
  -
    type: text
    text: |
      {{ search:results collection:is="articles"}}
          <a href="{{ url }}">{{ title }}</a>
      {{ /search:results }}
seo:
  title: 'Sökresultat: {{ get:q | sanitize }}'
fieldset: page
id: 90cd2cb5-78c2-489f-929e-ee9bda404ec2
