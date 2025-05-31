FROM node:18

# Imposta la working directory nel container
WORKDIR /app

# Copia solo i file package.json e lockfile per la fase di installazione
COPY package*.json ./

# Installa le dipendenze
RUN npm install

# Copia tutto il codice nel container (verrà sovrascritto dal volume live)
COPY . .

# Espone la porta usata da Nuxt in dev
EXPOSE 3000
