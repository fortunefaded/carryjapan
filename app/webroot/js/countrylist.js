var countryList = {
	'Bangladesh': 'zone1',
	'Bhutan': 'zone1',
	'Brunei': 'zone1',
	'Cambodia': 'zone1',
	'China ': 'zone1',
	'Guam': 'zone1',
	'Hong Kong': 'zone1',
	'India': 'zone1',
	'Indonesia': 'zone1',
	'Korea': 'zone1',
	'Laos': 'zone1',
	'Macao': 'zone1',
	'Malaysia': 'zone1',
	'Maldives': 'zone1',
	'Mongolia': 'zone1',
	'Myanmar': 'zone1',
	'Nepal': 'zone1',
	'Pakistan': 'zone1',
	'Philippines': 'zone1',
	'Saipan': 'zone1',
	'Singapore': 'zone1',
	'Sri Lanka': 'zone1',
	'Taiwan': 'zone1',
	'Thailand': 'zone1',
	'Vietnam': 'zone1',
	'Australia': 'zone2A',
	'Fiji': 'zone2A',
	'New Caledonia': 'zone2A',
	'New Zealand': 'zone2A',
	'Papua New Guinea': 'zone2A',
	'Solomon Is': 'zone2A',
	'Barbados': 'zone2A',
	'Canada': 'zone2A',
	'Costa Rica': 'zone2A',
	'Cuba': 'zone2A',
	'El Salvador': 'zone2A',
	'Honduras': 'zone2A',
	'Jamaica': 'zone2A',
	'Mexico': 'zone2A',
	'Panama': 'zone2A',
	'Trinidad and Tobago': 'zone2A',
	'U.S.A.': 'zone2A',
	'Bahrain': 'zone2A',
	'Cyprus': 'zone2A',
	'Iran': 'zone2A',
	'Iraq': 'zone2A',
	'Israel': 'zone2A',
	'Jordan': 'zone2A',
	'Kuwait': 'zone2A',
	'Oman': 'zone2A',
	'Qatar': 'zone2A',
	'Saudi Arabia': 'zone2A',
	'Syria': 'zone2A',
	'Turkey': 'zone2A',
	'United Arab Emirates': 'zone2A',
	'Austria': 'zone2B',
	'Azerbaidjan': 'zone2B',
	'Belarus': 'zone2B',
	'Belgium': 'zone2B',
	'Bulgaria': 'zone2B',
	'Czech': 'zone2B',
	'Croatia': 'zone2B',
	'Denmark': 'zone2B',
	'Estonia': 'zone2B',
	'Finland': 'zone2B',
	'France': 'zone2B',
	'Germany': 'zone2B',
	'Greece': 'zone2B',
	'Hungary': 'zone2B',
	'Iceland': 'zone2B',
	'Ireland': 'zone2B',
	'Italy': 'zone2B',
	'Latvia': 'zone2B',
	'Liechtenstein': 'zone2B',
	'Lithuania': 'zone2B',
	'Luxembourg': 'zone2B',
	'Macedonia': 'zone2B',
	'Malta': 'zone2B',
	'Netherlands': 'zone2B',
	'Norway': 'zone2B',
	'Poland': 'zone2B',
	'Portugal': 'zone2B',
	'Romania': 'zone2B',
	'Russia': 'zone2B',
	'Slovak': 'zone2B',
	'Slovenia': 'zone2B',
	'Spain': 'zone2B',
	'Sweden': 'zone2B',
	'Switzerland': 'zone2B',
	'Ukraine': 'zone2B',
	'U.K.': 'zone2B',
	'Argentina': 'zone3',
	'Brazil': 'zone3',
	'Chile': 'zone3',
	'Colombia': 'zone3',
	'Ecuador': 'zone3',
	'Paraguay': 'zone3',
	'Peru': 'zone3',
	'Uruguay': 'zone3',
	'Venezuela': 'zone3',
	'Algeria': 'zone3',
	'Botswana': 'zone3',
	'Cote d\'Ivoire': 'zone3',
	'Djibouti': 'zone3',
	'Egypt': 'zone3',
	'Ethiopia': 'zone3',
	'Gabon': 'zone3',
	'Ghana': 'zone3',
	'Kenya': 'zone3',
	'Madagascar': 'zone3',
	'Mauritius': 'zone3',
	'Morocco': 'zone3',
	'Nigeria': 'zone3',
	'Rwanda': 'zone3',
	'Senegal': 'zone3',
	'Sierra Leone': 'zone3',
	'South Africa': 'zone3',
	'Sudan': 'zone3',
	'Tanzania': 'zone3',
	'Togo': 'zone3',
	'Tunisia': 'zone3',
	'Uganda': 'zone3',
	'Zimbabwe': 'zone3',
};
$(document).ready(function(){
	for(var i in countryList){
		$('#UserAddressCountry').append('<option value="'+ i +'">' + i + '</option>');
	}
})